<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Membership;
use App\Models\MembershipModification;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\State;
use App\Models\VehicleColor;
use App\Services\GatewayService;
use App\Services\OnScreenNotificationService;
use App\Services\PayeezyService;
use Exception;
use FPCS\FlexiblePageCms\Services\CmsRoute;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use TCG\Voyager\Facades\Voyager;

class CheckoutController extends Controller
{
    /**
     * Checkout: route to correct screen
     */
    public function index(): RedirectResponse
    {
        if (Auth::user()) {
            $params = [];

            if (Session::get('isNewlyLoggedIn')) {
                array_push($params, 'hide-summary');
            }

            // Make sure taxes for Wash Clubs are accurate for current User
            $location = Auth::user()->home_location;
            Cart::updateTaxesForWashClubProducts($location);
            Cart::save();

            if (Cart::hasPhysicalProducts()) {
                return redirect()->route('checkout.shipping', $params);
            } else {
                return redirect()->route('checkout.review', $params);
            }
        }

        // Make sure taxes for Wash Clubs are nulled - user is not logged in
        Cart::updateTaxesForWashClubProducts(null);
        Cart::save();

        return redirect()->route('checkout.account');
    }

    /**
     * Show Account / Contact Info screen
     *
     * @return mixed
     */
    public function showAccount(Request $request)
    {
        if (Auth::user()) {
            return self::afterAccount();
        }

        Cart::updateAndSave();

        $hideSummary = Cart::hasWashClubProducts();

        Session::put('redirectTo', route('checkout.account'));

        return parent::view('checkout.account', compact([ 'hideSummary' ]));
    }

    /**
     * Store Account / Contact Info
     */
    public function storeAccount(Request $request): ?RedirectResponse
    {
        if (Auth::user()) {
            // User should never be logged in when POSTing to this route
            self::afterAccount($request);
        }

        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $email = $request->get('email');

        // Verify email is valid and not taken
        try {
            GatewayService::post('v2/validate-email', [ 'email' => $email ], [
                'useAppToken' => true,
                'throw_exception' => true,
            ]);
        } catch (Exception $e) {
            $error = (json_decode($e->getResponse()->getBody()->getContents() ?? null) ?? (object)[])->message ?? 'Unable to validate your email address.';

            return Redirect::to(route('checkout.account').'#register')
                ->withInput()
                ->withErrors([ 'email' => $error ]);
        }

        // Verify names are real
        $names = [ 'first_name' => $firstName, 'last_name' => $lastName ];
        try {
            GatewayService::post('v2/validate-names', [ 'names' => $names ], [
                'useAppToken' => true,
                'throw_exception' => true,
            ]);
        } catch (Exception $e) {
            $errors = (json_decode($e->getResponse()->getBody()->getContents() ?? null) ?? (object)[])->errors ?? array_reduce(array_keys($names), function ($acc, $key) {
                $acc->{$key} = 'Unable to validate your name.';

                return $acc;
            }, (object)[]);

            return Redirect::to(route('checkout.account').'#register')
                ->withInput()
                ->withErrors($errors);
        }

        if (Cart::hasDigitalProducts()) {
            // Automatically re-submit the same form, but to gateway.oauth_register_url in order to create the user on gateway
            $inputs = collect(array_keys($request->all()))->reduce(function ($acc, $key) use ($request) {
                $acc .= '<input type="hidden" name="'.$key.'" value="'.$request->get($key).'" />';

                return $acc;
            }, '');
            $inputs .= '<input type="hidden" name="redirectAfterRegister" value="'.($request->get('redirectAfterRegister') ?? GatewayService::redirectRoute('sso.after-register')).'" />';
            $form = '<form id="theform" action="'.config('services.gateway.base_url').config('services.gateway.oauth_register_url').'" method="post">'.$inputs.'</form>';
            $html = '<html><body>'.$form.'<script>document.querySelector("#theform").submit();</script></body></html>';

            response($html)->send();

            return null;
        } else {
            // Account not required, store contact info to session for later use
            Session::put('user.first_name', $firstName);
            Session::put('user.last_name', $lastName);
            Session::put('user.email', $email);
        }

        return self::afterAccount();
    }

    /**
     * Determine where to go after account screen
     */
    private static function afterAccount(): RedirectResponse
    {
        Session::reflash();

        return Redirect::to(self::getNextRouteAfterAccount());
    }

    /**
     * Return the route to go to after account screen
     */
    private static function getNextRouteAfterAccount(): string
    {
        if (Cart::hasPhysicalProducts()) {
            return route('checkout.shipping');
        }

        return route('checkout.review');
    }

    /**
     * Show Review screen
     */
    public function showReview(Request $request)
    {
        Cart::updateAndSave();

        $nextRoute = self::getNextRouteAfterShipping();

        return parent::view('checkout.review', compact([ 'nextRoute' ]));
    }

    /**
     * Show shipping screen
     */
    public function showShipping()
    {
        Cart::updateAndSave();

        return parent::view('checkout.shipping');
    }

    /**
     * Store Shipping Info
     */
    public function storeShipping(Request $request): RedirectResponse
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'phone' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        rescue(
            function () use ($validator) {
                $validator->validate();
            }
        );
        if ($validator->fails()) {
            $errors = $validator->errors();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors($errors);
        }

        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $address = $request->get('address');
        $city = $request->get('city');
        $state = $request->get('state');
        $zip = $request->get('zip');
        $phone = $request->get('phone');

        if (Auth::user()) {
            $response = GatewayService::updateUser([
                'shipping_first_name' => $firstName,
                'shipping_last_name' => $lastName,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'phone' => $phone,
            ]);

            if (!$response->success) {
                return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->with(OnScreenNotificationService::with([
                        'message' => 'Were were unable to save your information.',
                        'additionalInfo' => $response->error,
                        'level' => 'error',
                    ]));
            }
        } else {
            Session::put('user.shipping.first_name', $firstName);
            Session::put('user.shipping.last_name', $lastName);
            Session::put('user.shipping.address', $address);
            Session::put('user.shipping.city', $city);
            Session::put('user.shipping.state', $state);
            Session::put('user.shipping.zip', $zip);
            Session::put('user.shipping.phone', $phone);
        }

        return self::afterShipping();
    }

    /**
     * Determine where to go after shipping screen
     */
    private static function afterShipping(): RedirectResponse
    {
        return Redirect::to(self::getNextRouteAfterShipping());
    }

    /**
     * Return the route to go to after shipping screen
     */
    private static function getNextRouteAfterShipping(): string
    {
        if (Cart::hasWashClubProducts()) {
            if (Auth::user()->home_location) {
                return route('checkout.memberships.home-car-wash.show');
            } else {
                return route('checkout.memberships.home-car-wash.edit');
            }
        }

        return route('checkout.payment-methods.index');
    }

    /**
     * Show Home Car Wash screen
     */
    public function showHomeCarWash(Request $request)
    {
        $location = Auth::user()->home_location;

        $hideSummary = !Cart::isTaxFullyCalculated();
        if (!Session::get('isNewlyLoggedIn') && !Session::get('isJustSavedHomeLocation')) {
            $hideSummary = true;
        }

        Cart::updateTaxesForWashClubProducts($location);
        Cart::save();

        $nextRoute = self::getNextRouteAfterHomeCarWash();

        return parent::view('checkout.memberships.home-car-wash.show', compact([
            'hideSummary',
            'location',
            'nextRoute',
        ]));
    }

    /**
     * Edit Home Car Wash screen
     */
    public function editHomeCarWash(Request $request)
    {
        $location = Auth::user()->home_location;

        return parent::view('checkout.memberships.home-car-wash.edit-add', compact('location'));
    }

    /**
     * Save Home Car Wash submission
     */
    public function storeHomeCarWash(Request $request): RedirectResponse
    {
        $rules = [ 'location_id' => 'required|exists:locations,id' ];
        $messages = [ 'location_id.required' => 'You must select a car wash location.' ];
        $validator = Validator::make($request->all(), $rules, $messages);
        rescue(
            function () use ($validator) {
                $validator->validate();
            }
        );
        if ($validator->fails()) {
            $errors = $validator->errors();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors($errors);
        }

        $user = Auth::user();
        $user->home_location_id = $request->get('location_id');
        // Also store to WashConnect as `customer.siteId` (probably optional)
        $user->save();

        Session::flash('isJustSavedHomeLocation', true);

        return Redirect::route('checkout.memberships.home-car-wash.show')
            ->with(OnScreenNotificationService::with([
                'message' => 'Your home car wash has been saved.',
                'level' => 'success',
            ]));
    }

    /**
     * Return the route to go to after shipping screen
     */
    private static function getNextRouteAfterHomeCarWash(): string
    {
        $membershipIndex = 0;

        self::setCurrentMembershipIndex($membershipIndex);

        return route('checkout.memberships.details.edit', [ 'index' => $membershipIndex ]);
    }

    /**
     * Shows terms page
     */
    public function showMembershipDetails(string $index)
    {
        if (!$cartItem = Cart::getWashClubProductByIndex($index)) {
            self::abort(404);
        }

        self::setCurrentMembershipIndex((int)$index);

        if ($cartItem->options['is_modification_flow'] ?? null) {
            Session::put('checkout.memberships.'.$index.'.options', [
                'is_gift' => false,
                'modifies_membership_wash_connect_id' => $cartItem->options['modifies_membership_wash_connect_id'],
            ]);

            return redirect()->route('checkout.memberships.modification-summary', [ 'index' => $index ]);
        } elseif ($cartItem->options['reactivates_membership_wash_connect_id'] ?? null) {
            Session::put('checkout.memberships.'.$index.'.options', [
                'is_gift' => false,
                'reactivates_membership_wash_connect_id' => $cartItem->options['reactivates_membership_wash_connect_id'],
            ]);

            return redirect()->route('checkout.memberships.modification-summary', [ 'index' => $index ]);
        }

        $isGiftable = Cart::isItemGiftable($cartItem);
        $colors = VehicleColor::all();
        $states = State::all();

        //check if there are too many clubs for WC be queried for modification possibilities
        $availableMembershipCount = Membership::getForCurrentUser()->count();
        $isModificationBlocked = false;
        if ($availableMembershipCount > config('cart.max_modifications_to_check')) {
            $availableMemberships = collect();
            $isModificationBlocked = true;
        } else {
            $availableMemberships = self::getMembershipsAvailableForModificationOrReactivation((int)$index);
        }
        $availableVehicles = self::getVehiclesAvailableForNewMembership((int)$index);

        $showAssociateVehicleControl = false;
        if (!$isModificationBlocked && !count($availableMemberships)) {
            $showAssociateVehicleControl = true;
        }

        $isGift = Session::get('checkout.memberships.'.$index.'.options.is_gift');
        $membershipMultiId = Session::get('checkout.memberships.'.$index.'.options.modifies_membership_wash_connect_id').'|'.Session::get('checkout.memberships.'.$index.'.options.modifies_membership_id');
        if ($membershipMultiId === '|') {
            if (!empty($cartItem->options['modifies_membership_wash_connect_id'])) {
                $membershipMultiId = $cartItem->options['modifies_membership_wash_connect_id'].'|'.($cartItem->options['modifies_membership_id'] ?? '');
            } else {
                $membershipMultiId = null;
            }
        }
        $vehicleId = Session::get('checkout.memberships.'.$index.'.options.vehicle_id');

        return parent::view('checkout.memberships.details', compact([
            'availableMemberships',
            'availableMembershipCount',
            'isModificationBlocked',
            'showAssociateVehicleControl',
            'availableVehicles',
            'cartItem',
            'colors',
            'index',
            'isGift',
            'isGiftable',
            'membershipMultiId',
            'states',
            'vehicleId',
        ]));
    }

    /**
     * Return array of Vehicles that are available for a new Membership
     */
    private static function getVehiclesAvailableForNewMembership(int $index): array
    {
        $alreadyUsedVehicleIds = collect(array_keys(Session::get('checkout.memberships') ?? []))->reduce(function ($acc, $index) {
            if (!$checkoutMembership = Session::get('checkout.memberships.'.$index)) {
                return $acc;
            }

            if ($checkoutMembership['options']['vehicle_id'] ?? null) {
                $acc[$index] = $checkoutMembership['options']['vehicle_id'];
            }

            return $acc;
        }, []);
        $vehicles = collect(GatewayService::getVehiclesForCurrentUser([ 'available' => true ]) ?? [])
            ->filter(function ($vehicle) use ($alreadyUsedVehicleIds, $index) {
                if (!($vehicle->id ?? $vehicle->wash_connect_id ?? null)) {
                    return false;
                }

                if (($vehicle->id ?? null) && in_array($vehicle->id, $alreadyUsedVehicleIds) && array_search($vehicle->id, $alreadyUsedVehicleIds) !== (int)$index) {
                    return false;
                }

                return true;
            })
            ->toArray();

        return $vehicles;
    }

    /**
     * Return Collection of Memberships available for Modification
     */
    private static function getMembershipsAvailableForModificationOrReactivation(int $index): Collection
    {
        if (!$cartItem = Cart::getWashClubProductByIndex($index)) {
            self::abort(404);
        }

        $alreadyUsedMembershipWashConnectIds = collect(array_keys(Session::get('checkout.memberships') ?? []))->reduce(function ($acc, $index) {
            if (!$checkoutMembership = Session::get('checkout.memberships.'.$index)) {
                return $acc;
            }

            if ($checkoutMembership['options']['modifies_membership_wash_connect_id'] ?? null) {
                $acc[$index] = $checkoutMembership['options']['modifies_membership_wash_connect_id'];
            }
            if ($checkoutMembership['options']['reactivates_membership_wash_connect_id'] ?? null) {
                $acc[$index] = $checkoutMembership['options']['reactivates_membership_wash_connect_id'];
            }

            return $acc;
        }, []);

        $variant = ProductVariant::find($cartItem->options['variant_id'] ?? null);

        $memberships = Membership::getForCurrentUser()
            ->filter(function ($membership) use ($variant) {
                // Can be modified
                if ($membership->is_active
                    && (!$membership->pending_modification || ($membership->pending_modification->is_cancelable ?? null))
                    && $membership->canBeModifiedTo($variant)
                ) {
                    return true;
                }

                // Can be reactivated (https://bitbucket.org/flowerpress/brownbear.com/issues/1051/enable-reactivate-and-optionally-change)
                if ($membership->wash_connect->membership && $membership->is_fully_terminated) {
                    return true;
                }

                return false;
            })
            ->filter(function ($membership) use ($alreadyUsedMembershipWashConnectIds, $index) {
                if (($membership->wash_connect->membership->id ?? null) && in_array($membership->wash_connect->membership->id, $alreadyUsedMembershipWashConnectIds) && array_search($membership->wash_connect->membership->id, $alreadyUsedMembershipWashConnectIds) !== (int)$index) {
                    return false;
                }

                return true;
            })
            ->values();

        return $memberships;
    }

    /**
     * Shows terms page
     *
     * @return mixed
     */
    public function storeMembershipDetails(Request $request, string $index)
    {
        if (!$cartItem = Cart::getWashClubProductByIndex($index)) {
            self::abort(404);
        }

        $isGiftable = Cart::isItemGiftable($cartItem);

        $vehicleIdRequired = (!$request->input('is_gift') || $request->input('is_gift') === 'false') && $request->input('is_modification_or_reactivation') !== 'true';

        $vehicleFieldsRequired = $request->get('vehicle_id') === 'new';

        //check if there are too many clubs for WC be queried for modification possibilities
        $totalMembershipCount = Membership::getForCurrentUser()->count();
        if ($totalMembershipCount > config('cart.max_modifications_to_check')) {
            //if there are so many membership that its certain the call for "available for modifications" will take too long
            //to query WC and check variants, assume there are modifications available.
            $availableMembershipCount = $totalMembershipCount;
        } else {
            $availableMembershipCount = count(self::getMembershipsAvailableForModificationOrReactivation((int)$index));
        }


        $rules = [
            'is_gift' => Rule::requiredIf($isGiftable),
            'is_modification_or_reactivation' => Rule::requiredIf($isGiftable && $request->get('is_gift') === 'false' && $availableMembershipCount),
            'membership_multi_id' => 'required_if:is_modification_or_reactivation,"true"',
            'vehicle_id' => Rule::requiredIf($vehicleIdRequired),
            'year' => Rule::requiredIf($vehicleFieldsRequired),
            'make' => Rule::requiredIf($vehicleFieldsRequired),
            'model' => Rule::requiredIf($vehicleFieldsRequired),
            'color_id' => [ Rule::requiredIf($vehicleFieldsRequired), 'int' ],
            'license_plate_number' => [ Rule::requiredIf($vehicleFieldsRequired), 'alpha_num'],
            'license_plate_state' => Rule::requiredIf($vehicleFieldsRequired),
        ];
        $messages = [
            'is_gift.required' => 'Please specify whether or not this club is a gift',
            'is_modification_or_reactivation.required' => 'Please specify whether or not this is a new club membership',
            'membership_multi_id.required_if' => 'Please select a club membership',
            'vehicle_id.required' => 'Please choose a vehicle',
            'year.required' => 'Please enter a vehicle year',
            'make.required' => 'Please enter a vehicle make',
            'model.required' => 'Please enter a vehicle model',
            'color_id.required' => 'Please select a vehicle color',
            'license_plate_number.required' => 'Please enter a license plate number',
            'license_plate_state.required' => 'Please select a license plate state',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        rescue(
            function () use ($validator) {
                $validator->validate();
            }
        );
        if ($validator->fails()) {
            $errors = $validator->errors();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors($errors);
        }

        $isGift = $request->get('is_gift') === 'true';

        $user = Auth::user();

        $options = [];
        if (!$isGift) {
            $options['is_gift'] = false;

            if ($request->get('vehicle_id') === 'new') {
                // Store Vehicle
                try {
                    $vehicle = GatewayService::post(
                        '/v2/vehicles',
                        [
                            'year' => $request->get('year'),
                            'make' => $request->get('make'),
                            'model' => $request->get('model'),
                            'color_id' => $request->get('color_id'),
                            'license_plate_number' => $request->get('license_plate_number'),
                            'license_plate_state' => $request->get('license_plate_state'),
                            'wash_connect_site_id' => $user->home_location->wash_connect_id ?? null,
                        ],
                        [ 'throw_exception' => true ]
                    );
                } catch (RequestException $e) {
                    $response = (object)json_decode($e->getResponse()->getBody()->getContents());

                    if ($response->message === 'License plate number already exists.') {
                        if ($response->data->is_mine) {
                            $errorMessage = 'You already have a vehicle saved with this license plate number and state. Please select it in the dropdown above.';
                        } else {
                            $errorMessage = 'A vehicle already exists with this license plate number and state. Please enter a different license plate number.';
                        }

                        $errors = [ 'license_plate_number' => $errorMessage ];
                    } elseif (count((array)($response->errors ?? []))) {
                        $errors = $response->errors;
                    } else {
                        $errors = [ 'vehicle_id' => 'An unknown error has occurred. Unable to save your vehicle selection.' ];
                    }

                    return redirect()
                        ->back()
                        ->withInput($request->all())
                        ->withErrors($errors);
                }

                $options['vehicle_id'] = $vehicle->data->id;
            } elseif ($request->input('is_modification_or_reactivation') === 'true') {
                list($modifiesWashConnectId, $modifiesMembershipId) = explode('|', $request->input('membership_multi_id'));

                $membershipToModify = Membership::getByWashConnectIdAndCustomerEmail($modifiesWashConnectId, Auth::user()->email);

                if ($membershipToModify->is_reactivatable ?? null) {
                    $options['reactivates_membership_wash_connect_id'] = $modifiesWashConnectId;
                    $options['reactivates_membership_id'] = $modifiesMembershipId;
                } else {
                    $options['modifies_membership_wash_connect_id'] = $modifiesWashConnectId;
                    $options['modifies_membership_id'] = $modifiesMembershipId;
                }
            } else {
                $options['vehicle_id'] = $request->get('vehicle_id');
            }
        } else {
            $options['is_gift'] = true;
            $options['vehicle_id'] = null;
        }

        Session::put('checkout.memberships.'.$index.'.options', $options);

        if ($request->input('is_modification_or_reactivation') === 'true') {
            $nextRoute = route('checkout.memberships.modification-summary', [ 'index' => $index ]);
        } elseif ($nextIndex = self::nextMembershipIndex()) {
            $nextRoute = route('checkout.memberships.details.edit', [ 'index' => $nextIndex ]);
        } else {
            $nextRoute = route('checkout.memberships.terms');
        }

        return Redirect::to($nextRoute);
    }

    /**
     * Shows modification summary page
     */
    public function showMembershipModificationSummary()
    {
        if ($nextIndex = self::nextMembershipIndex()) {
            $nextRoute = route('checkout.memberships.details.edit', [ 'index' => $nextIndex ]);
        } else {
            $nextRoute = route('checkout.memberships.terms');
        }

        $index = self::getCurrentMembershipIndex();
        $cartItem = Cart::getWashClubProductByIndex($index);
        $variant = ProductVariant::find($cartItem->options['variant_id']);
        $modification = new MembershipModification([
            'order_product' => [
                'product_id' => $variant->product->id,
                'product_variant_id' => $variant->id,
            ],
            'club' => (object)[
                'display_name' => $cartItem->model->name,
                'display_name_with_term' => $cartItem->name,
                'term' => $cartItem->options['variant_name'],
            ],
        ]);

        $washConnectId = Session::get('checkout.memberships.'.$index.'.options.modifies_membership_wash_connect_id', null);
        if (!$washConnectId) {
            $washConnectId = Session::get('checkout.memberships.'.$index.'.options.reactivates_membership_wash_connect_id', null);
            $modification->is_reactivation = true;
        }
        if (!$washConnectId || !$membership = Membership::getByWashConnectIdAndCustomerEmail($washConnectId, Auth::user()->email)) {
            abort(400);
        }

        return parent::view('checkout.memberships.modification-summary', compact('membership', 'modification', 'nextRoute'));
    }

    /**
     * Return the next wash club in the cart
     */
    private static function nextMembershipIndex(): ?int
    {
        $currentIndex = self::getCurrentMembershipIndex();
        if ($currentIndex === null) {
            $nextIndex = 0;
        } else {
            $nextIndex = $currentIndex + 1;
        }

        if ($nextIndex >= count(Cart::getWashClubInstances())) {
            return null;
        }

        return $nextIndex;
    }

    /**
     * Set the index of the current wash club
     */
    private static function setCurrentMembershipIndex(?int $index = null): void
    {
        Session::put('currentCheckoutMembershipIndex', $index ?? null);
    }

    /**
     * Get the index of the current wash club
     */
    private static function getCurrentMembershipIndex(): ?int
    {
        return Session::get('currentCheckoutMembershipIndex', null);
    }

    /**
     * Shows terms page
     */
    public function showMembershipTerms()
    {
        $content = GatewayService::getCurrentTermsContent();

        return parent::view('checkout.memberships.terms', compact('content'));
    }

    /**
     * Show payment methods screen
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function showPaymentMethods(Request $request)
    {
        Session::forget('duplicatePaymentRecovery');

        $user = Auth::user();

        $paymentMethod = $user->wash_connect->payment_method ?? null;

        if (!($paymentMethod && $paymentMethod->is_payeezy) && !Session::get('notifications')) {
            return redirect()->route('checkout.payment-methods.edit');
        }

        $hasNonGiftWashClubProducts = Cart::hasNonGiftWashClubProducts();

        return parent::view('checkout.payment-methods.index', compact('hasNonGiftWashClubProducts', 'paymentMethod', 'user'));
    }

    /**
     * Process payment method selection
     */
    public function storePaymentMethodSelection(Request $request): RedirectResponse
    {
        $rules = [ 'payment_method' => 'required|in:existing,new' ];
        if (Auth::user() && !Auth::user()->email_verified_at) {
            $rules['email_verification'] = 'required';
        }
        $messages = [
            'payment_method.required' => 'Please select a payment method',
            'email_verification.required' => 'You must verify your email before completing payment.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        rescue(
            function () use ($validator) {
                $validator->validate();
            }
        );
        if ($validator->fails()) {
            $errors = $validator->errors();

            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors($errors);
        }

        if ($request->input('payment_method') === 'new') {
            return redirect()->route('checkout.payment-methods.edit');
        }

        $order = self::createOrUpdateUnpaidOrder();

        return $this->completeOrder($order, $request);
    }

    /**
     * Show payment form screen
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function editPaymentMethod(Request $request)
    {
        // Require either logged-in state or email address - closes hole that would allow edge case where neither are present
        if (!Auth::user() && !Session::get('user.email')) {
            return redirect()->route('checkout.account');
        }

        $order = self::createOrUpdateUnpaidOrder();

        $paymentForm = PayeezyService::paymentForm([
            'amount' => 0,
            'customerFirstName' => $order->user->first_name ?? $order->customer_first_name,
            'customerId' => Auth::user()->id ?? null,
            'customerLastName' => $order->user->last_name ?? $order->customer_last_name,
            'description' => 'Order #'.$order->id.' on brownbear.com',
            'discountAmount' => $order->discount,
            'invoiceNumber' => $order->id,
            'responseUrl' => route('checkout.payment-methods.storeNew'),
            'tax' => $order->tax,
            'taxRate' => $order->tax_rate,
            'transactionType' => 'AUTH_ONLY',
            'zip' => $order->user->billing_zip ?? $order->shipping_zip,
        ]);

        return parent::view('checkout.payment-methods.edit', compact([ 'paymentForm' ]));
    }

    /**
     * Create an unpaid Order in the database, or use existing if it exists
     */
    private static function createOrUpdateUnpaidOrder(): Order
    {
        $order = Order::find(Session::get('checkout.orderId')) ?? new Order();

        $order->user_id = Auth::user() ? Auth::user()->id : null;
        $order->user_email = !Auth::user() ? Session::get('user.email') : null;
        $order->customer_first_name = !Auth::user() ? Session::get('user.first_name') : null;
        $order->customer_last_name = !Auth::user() ? Session::get('user.last_name') : null;
        $order->discount = Cart::discount();
        $order->sub_total = Cart::subTotal();
        $order->shipping_price = Cart::shippingPrice();
        $order->tax_rate = (float)Voyager::setting('sales-tax.global');
        $order->tax = Cart::tax();
        $order->total = Cart::total();
        $order->shipping_first_name = Auth::user() ? Auth::user()->shipping->first_name : Session::get('user.shipping.first_name');
        $order->shipping_last_name = Auth::user() ? Auth::user()->shipping->last_name : Session::get('user.shipping.last_name');
        $order->shipping_address = Auth::user() ? Auth::user()->shipping->address : Session::get('user.shipping.address');
        $order->shipping_city = Auth::user() ? Auth::user()->shipping->city : Session::get('user.shipping.city');
        $order->shipping_state = Auth::user() ? Auth::user()->shipping->state : Session::get('user.shipping.state');
        $order->shipping_zip = Auth::user() ? Auth::user()->shipping->zip : Session::get('user.shipping.zip');
        $order->shipping_phone = Auth::user() ? Auth::user()->shipping->phone : Session::get('user.shipping.phone');
        $order->save(); // Need to save here to ensure that the order has an id

        $order->products()->detach();
        foreach (Cart::content() as $row) {
            $product = Product::findOrFail($row->id);

            for ($x = 0; $x < ($product->is_wash_club ? (int)$row->qty : 1); $x++) {
                if ($product->is_wash_club) {
                    $qty = 1;
                    $tax = $row->tax / $row->qty;
                    $total = $row->total / $row->qty;
                } else {
                    $qty = $row->qty;
                    $tax = $row->tax;
                    $total = $row->total;
                }

                if ($row->preDiscountPrice && $row->preDiscountPrice > $row->price) {
                    $preDiscountSubTotal = $row->preDiscountPrice * $qty * 100;
                    if ($product->is_wash_club) {
                        $discount = ($row->preDiscountPrice - $row->price + ($row->discount / $row->$qty)) * $qty * 100;
                    } else {
                        $discount = ($row->preDiscountPrice - $row->price + $row->discount) * $qty * 100;
                    }
                } else {
                    $preDiscountSubTotal = $row->price * $qty * 100;
                    $discount = $row->discount * $qty * 100;
                }

                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $row->options->variant_id ?? null,
                    'coupon_code' => $row->options->couponCode ?? null,
                    'purchase_price_ea' => $row->price * 100,
                    'num_washes_ea' => $product->num_washes ?? null,
                    'qty' => $qty,
                    'pre_discount_sub_total' => $preDiscountSubTotal,
                    'discount' => $discount,
                    'sub_total' => $product->is_wash_club ? $preDiscountSubTotal - $discount : $row->subtotal * 100,
                    'tax' => $tax * 100,
                    'total' => $total * 100,
                    'created_at' => Carbon::now(),
                ]);
            }

            $options = $row->options;
            $options['order_product_ids'] = $orderProduct->order_id.'.'.$orderProduct->product_id.'.'.$orderProduct->product_variant_id;
            Cart::update($row->rowId, [ 'options' => $options ]);
        }
        // Set status after applying products (since status depends on what types of products are included on Order)
        $order = $order->setStatus(Order::STATUS_UNPAID);
        $order->save();

        Session::put('checkout.orderId', $order->id);

        return $order;
    }

    /**
     * Store response from payment
     */
    public function storeNewPaymentMethod(Request $request): RedirectResponse
    {
        if (!$order = Order::find($request->get('x_invoice_num'))) {
            self::abort(404, ['message' => 'The order for the passed payment could not be found.']);
        }

        $this->paymentAuth = PayeezyService::parsePaymentFormResponse($request);

        if (!$this->paymentAuth->success) {
            return $this->paymentFailed($order);
        }

        // The completed Payeezy form transaction was AUTH_ONLY, and the funds have not yet been captured.
        // Proceed to capture the funds

        $paymentMethod = PayeezyService::getPaymentMethodFromPaymentFormResponse($request);

        $authTransactionId = PayeezyService::getTransactionIdFromPaymentFormResponse($request);

        if (Cart::hasNonGiftWashClubProducts()) {
            $response = GatewayService::setPaymentMethod($paymentMethod);

            if (!$response->success) {
                Log::error('Error with setPaymentMethod: '.json_encode($response ?? null).'.');
                return redirect()
                    ->route('checkout.payment-methods.index')
                    ->with(OnScreenNotificationService::with([
                        'message' => $response->message,
                        'level' => 'error',
                    ]));
            }

            try {
                $existingPaymentMethod = Auth::user()->wash_connect->payment_method ?? null;
            } catch (Exception $e) {
                Log::error('Unable to load existingPaymentMethod: '.json_encode($e));
            }

            if ($existingPaymentMethod) {
                $brand = Str::title($paymentMethod->brand);
                $last4 = substr($paymentMethod->token, -4);
                OnScreenNotificationService::flash([
                    'message' => 'Your payment method has changed to a '.$brand.' ending in '.$last4.'. All recurring charges for monthly or yearly clubs associated with your Brown Bear Digital account will be billed to the new payment method. If you have questions, <a href="'.CmsRoute::get('support/contact-us').'?show=email">contact support</a>.',
                    'level' => 'info',
                ]);
            }
        }

        return $this->completeOrder($order, $request, $paymentMethod, $authTransactionId);
    }

    /**
     * Capture funds on stored payment method and finalize Order
     */
    private function completeOrder(Order $order, Request $request, ?object $paymentMethod = null, ?string $authTransactionId = null): RedirectResponse
    {
        if (!$paymentMethod) {
            $paymentMethod = Auth::user()->wash_connect->payment_method ?? null;
        }
        if (!$paymentMethod) {
            self::abort(404, ['message' => 'Cannot complete payment, payment method not found.']);
        } elseif (!$paymentMethod->is_payeezy) {
            self::abort(400, ['message' => 'Cannot complete payment, payment method needs to be updated into our new system.']);
        }

        $amountToCapture = $order->total;
        $index = 0;
        foreach ($order->products->filter(function ($product) {
            return $product->is_wash_club;
        }) as $product) {
            $modifiesWashConnectId = Session::get('checkout.memberships.'.$index.'.options.modifies_membership_wash_connect_id');

            if ($modifiesWashConnectId) {
                $membership = Membership::getByWashConnectIdAndCustomerEmail($modifiesWashConnectId, Auth::user()->email);
                if (!$membership) {
                    return $this->paymentFailed($order, 'Unable to find current membership expiration date.');
                }

                $ccMonth = substr($paymentMethod->expiration_date, 0, 2);
                $ccYear = substr($paymentMethod->expiration_date, -2);
                $ccExpiration = Carbon::createFromFormat('m-d-y', $ccMonth.'-01-'.$ccYear);

                if ($membership->expires_at->gt($ccExpiration)) {
                    return $this->paymentFailed($order, 'It looks like the card you selected has an expiration date before this club change will occur. Please enter a new card, so we can charge your card successfully.');
                }

                //if this product is a modification, the credit card will be charged when the modification runs
                //remove this products subtotal from the total
                //Why not One Year? https://bitbucket.org/flowerpress/brownbear.com/issues/890
                if ($product->pivot->variant->name !== 'One Year') {
                    $amountToCapture -= $product->pivot->total / 100;
                }
            }

            $index++;
        }

        $amountToCapture = round($amountToCapture, 2);
        if ($amountToCapture > 0) {
            $this->paymentCapture = PayeezyService::chargePaymentMethod((array)$paymentMethod, [
                'transactionType' => PayeezyService::API_TRANSACTION_TYPE_AUTH_CAPTURE,
                'transactionId' => $authTransactionId ?? null,
                'amount' => $amountToCapture,
                'orderId' => $order->id,
                'subsequentTransaction' => true,
                'merchantInitiated' => false,
                'scheduled' => false,
            ]);

            if (!$this->paymentCapture->success) {
                // Ignore duplicate transactions (https://bitbucket.org/flowerpress/brownbear.com/issues/1136/interstitial-payeezy-link-results-in)
                if ($this->paymentCapture->isDuplicate) {
                    return redirect()
                        ->route('checkout.success', [
                            Session::get('duplicatePaymentRecovery.orderId'),
                            Session::get('duplicatePaymentRecovery.orderAccessToken'),
                        ])
                        ->with($with ?? null);
                }

                return $this->paymentFailed($order);
            }
        }

        // Funds successfully captured
        // Proceed to update Order, etc

        $order = $order->setStatus(Order::STATUS_PENDING);
        $order->transaction_id = $this->paymentCapture->transactionId ?? null;
        $order->access_token = sha1($order->id.$order->transaction_id.($order->user ? $order->user->email : $order->user_email));
        $order->transaction_error = null;

        // Stash order id & access token in case a duplicate transactions occurs later - will be used to "ignore duplicate transactions" above, and bypass to success screen
        Session::put('duplicatePaymentRecovery.orderId', $order->id);
        Session::put('duplicatePaymentRecovery.orderAccessToken', $order->access_token);

        $order->save();

        $errors = [];

        // Update customer's billing zip on Gateway
        if (Auth::user()) {
            $zip = PayeezyService::getZipCodeFromPaymentFormResponse($request);
            $rules = [ 'zip' => 'required|min:5|max:10' ];
            $validator = Validator::make([ 'zip' => $zip ], $rules);

            if (!$validator->fails()) {
                try {
                    GatewayService::patch('/user', [ 'billing_zip' => $zip ], [ 'throw_exception' => true ]);
                } catch (RequestException $e) {
                    $userError = (object)json_decode($e->getResponse()->getBody()->getContents());

                    Log::error('Unable to update User billing_zip: '.($userError->message ?? 'Unknown error').':');
                    Log::error(json_encode($userError));

                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors($userError->errors ?? []);
                }
            } else {
                Log::error('Failed to validate zip from Payeezy: '.json_encode($zip).'.');
            }
        } else {
            Log::error('no auth user in checkout controller.');
        }

        // Generate Membership/MembershipModifications via Gateway
        $washClubs = [];
        $index = 0;
        foreach ($order->products as $product) {
            if ($product->is_wash_club) {
                try {
                    $modifiesWashConnectId = Session::get('checkout.memberships.'.$index.'.options.modifies_membership_wash_connect_id');
                    $reactivatesWashConnectId = Session::get('checkout.memberships.'.$index.'.options.reactivates_membership_wash_connect_id');
                    if (!!$modifiesWashConnectId) {
                        $humanActionStr = 'modify';

                        $modification = GatewayService::post(
                            'v2/membership-modifications',
                            [
                                'target_membership_wash_connect_id' => $modifiesWashConnectId,
                                'source' => 'brownbear.com',
                                'source_order_line_item_id' => $product->pivot->id,
                            ],
                            [ 'throw_exception' => true ]
                        );

                        if ($modification->data ?? null) {
                            $washClubs[] = (object)[
                                'data' => $modification->data,
                                'isMembershipModification' => true,
                                'product' => $product,
                            ];
                        }
                    } elseif (!!$reactivatesWashConnectId) {
                        $humanActionStr = 'reactivate';

                        $reactivation = GatewayService::post(
                            'v2/user/memberships/'.$reactivatesWashConnectId.'/reactivate',
                            [
                                'source' => 'brownbear.com',
                                'order_id' => $order->id,
                                'wash_connect_program_id' => $product->pivot->variant->wash_connect_program_id,
                            ],
                            [ 'throw_exception' => true ]
                        );

                        if ($reactivation->data ?? null) {
                            $product->pivot->reactivates_wash_connect_id = $reactivation->data->wash_connect->membership->id;
                            $product->pivot->save();

                            $washClubs[] = (object)[
                                'data' => $reactivation->data,
                                'isMembershipReactivation' => true,
                                'product' => $product,
                            ];
                        }
                    } else {
                        $humanActionStr = 'create';

                        $membershipPurchase = GatewayService::post(
                            'v2/membership-purchases',
                            [
                                'source' => 'brownbear.com',
                                'source_order_line_item_id' => $product->pivot->id,
                                'is_gift' => Session::get('checkout.memberships.'.$index.'.options.is_gift', false),
                                'order_id' => $order->id,
                                'payment' => [
                                    'expiration' => $paymentMethod->expiration_date,
                                    'cardtype' => $paymentMethod->brand,
                                    'last4' => substr($paymentMethod->token, -4),
                                    'cardToken' => $paymentMethod->token,
                                    'cardholder' => $paymentMethod->cardholder,
                                    'authCode' => $paymentMethod->auth_code ?? null,
                                ],
                                'location_wash_connect_id' => $order->user->home_location->wash_connect_id,
                                'sales_item_wash_connect_id' => $product->pivot->variant->wash_connect_id ?? null,
                                'vehicle_id' => Session::get('checkout.memberships.'.$index.'.options.vehicle_id', null),
                            ],
                            [ 'throw_exception' => true ]
                        );

                        if ($membershipPurchase->data ?? null) {
                            $washClubs[] = (object)[
                                'data' => $membershipPurchase->data,
                                'product' => $product,
                            ];
                        }
                    }
                } catch (RequestException $e) {
                    $body = json_decode((string)$e->getResponse()->getBody());
                    $message = $body->message ? $body->message : 'Unknown error';

                    $statusCode = $e->getResponse()->getStatusCode() ?? 'unknown';
                    $orderId = $order->id ?? 'unknown';
                    $customer = ($order->customer_full_name ?? 'unknown').' ('.($order->customer_email ?? 'unknown email').')';

                    Log::error('[Membership Checkout Fail] Unable to '.$humanActionStr.' membership:');
                    Log::error('Status Code: '.$statusCode);
                    Log::error('Order Id: '.$orderId);
                    Log::error('Customer: '.$customer);
                    Log::error('Message: '.$message);

                    $product->pivot->status_message = 'Unable to '.$humanActionStr.' membership. Status Code: '.$statusCode.'; Message: '.$message;
                    $product->pivot->save();

                    $errors[] = 'We were unable to '.$humanActionStr.' one of your memberships.';
                }

                $index++;
            }
        }

        // Increment coupons.num_uses
        foreach (Cart::content() as $item) {
            if (($item->options->couponId ?? null) && $coupon = Coupon::find($item->options->couponId)) {
                $coupon->num_uses += (int)$item->qty;

                $coupon->save();
            }
        }

        Cart::destroy();
        Cart::deleteSaved();

        // Email customer receipt
        $name = $order->user->first_name ?? $order->customer_first_name;
        $email = $order->user_email ?? $order->user->email;
        if (!($order->user && !$order->user->notification_pref_orders)) {
            try {
                Mail::send(
                    [
                        'html' => 'emails.order-received-html',
                        'text' => 'emails.order-received-text',
                    ],
                    [
                        'name' => $name,
                        'email' => $email,
                        'order' => $order,
                        'user' => $order->user ?? null,
                    ],
                    function ($message) use ($email, $name) {
                        $message->to($email, $name)
                            ->from(config('mail.from.address'), config('mail.from.name'))
                            ->subject('Your order has been received');
                    }
                );
            } catch (RequestException $e) {
                Log::error('Unable to send order confirmation email to '.$email.':');
                Log::error($e);
            }
        }

        // Email digital certificates for any Membership
        foreach ($washClubs as $washClub) {
            if (($washClub->isMembershipModification ?? null) || ($washClub->isMembershipReactivation ?? null)) {
                continue;
            }

            try {
                Mail::send(
                    [
                        'html' => 'emails.wash-club-certificate-html',
                        'text' => 'emails.wash-club-certificate-text',
                    ],
                    [
                        'name' => $name,
                        'email' => $email,
                        'user' => $order->user ?? null,
                        'washClub' => $washClub,
                    ],
                    function ($message) use ($email, $name) {
                        $message->to($email, $name)
                            ->from(config('mail.from.address'), config('mail.from.name'))
                            ->subject('Unlimited Washes Granted!');
                    }
                );
            } catch (RequestException $e) {
                Log::error('Unable to send wash club certificate email to '.$email.':');
                Log::error($e);
            }
        }

        if (count($errors)) {
            $with = OnScreenNotificationService::with([
                'message' => 'Some errors occurred. Please <a href="'.CmsRoute::get('support.contact-us').'">contact us</a> if you have any problems.',
                'additionalInfo' => implode(' ', array_unique($errors)),
                'level' => 'error',
            ]);
        }

        return redirect()
            ->route('checkout.success', [$order->id, $order->access_token])
            ->with($with ?? null);
    }

    /**
     * Handle failure state for storePayment()
     */
    private function paymentFailed(Order $order, ?string $message = null): RedirectResponse
    {
        $authFailed = ($this->paymentAuth ?? null) && !$this->paymentAuth->success ? true : false;
        $captureFailed = ($this->paymentCapture ?? null) && !$this->paymentCapture->success ? true : false;

        $status = $message ?? ($authFailed ? ($this->paymentAuth->message ?? null) : null) ?? ($captureFailed ? ($this->paymentCapture->message ?? null) : null) ?? 'Unable to process your payment.';
        $additionalInfo = ($authFailed ? ($this->paymentAuth->bank_message ?? null) : null) ?? ($captureFailed ? ($this->paymentCapture->bank_message ?? null) : null) ?? null;

        $order = $order->setStatus(Order::STATUS_FAILED_PAYMENT);
        $order->transaction_error = $status.($additionalInfo ? ' ('.$additionalInfo.')' : '');

        $order->save();

        return redirect()
            ->route('checkout.payment-methods.index')
            ->with(OnScreenNotificationService::with([
                'message' => $status,
                'additionalInfo' => $additionalInfo,
                'level' => 'error',
            ]));
    }

    /**
     * Show success screen
     *
     * @return mixed
     */
    public function showSuccess(string $orderId, ?string $accessToken = null)
    {
        if ($errorRedirect = self::handleSuccessScreenErrors($orderId, $accessToken)) {
            return $errorRedirect;
        }

        $order = self::verifyCheckoutSuccess($orderId, $accessToken);

        return parent::view('checkout.success', compact([
            'order',
            'accessToken',
        ]));
    }

    /**
     * Show success screen after new registration
     *
     * @return mixed
     */
    public function showRegistrationSuccess(string $orderId, ?string $accessToken = null)
    {
        if ($errorRedirect = self::handleSuccessScreenErrors($orderId, $accessToken)) {
            return $errorRedirect;
        }

        $order = self::verifyCheckoutSuccess($orderId, $accessToken);

        Session::flash('isNewlyRegistered', true);

        return redirect()
            ->route('checkout.success', [ $order->id ])
            ->with(OnScreenNotificationService::with([
                'message' => 'Your order has been saved.',
                'level' => 'success',
            ]));
    }

    /**
     * Show errors on success screen if necessary
     */
    private static function handleSuccessScreenErrors(string $orderId, ?string $accessToken = null): ?RedirectResponse
    {
        if (Session::has('errors') && Session::get('errors')->count()) {
            return redirect()
                ->route('checkout.success', [ $orderId, $accessToken ])
                ->with(OnScreenNotificationService::with([
                    'message' => collect(Session::get('errors')->getMessages())->first()[0],
                    'level' => 'error',
                ]));
        }

        return null;
    }

    /**
     * Verify that user has access to view requested Order for confirmation screen, and that Order is paid
     *
     * @return mixed
     */
    private static function verifyCheckoutSuccess(string $orderId, ?string $accessToken = null)
    {
        $order = Order::findOrFail($orderId);

        if ($accessToken && $accessToken !== $order->access_token) {
            self::abort(403);
        } elseif (Auth::user() && Auth::user()->id !== $order->user_id) {
            self::abort(403);
        }

        if ($order->merch_status !== Order::STATUS_PENDING && $order->club_status !== Order::STATUS_PENDING) {
            self::abort(500, ['message' => 'The order you requested does not appear to have been paid.']);
        }

        return $order;
    }
}
