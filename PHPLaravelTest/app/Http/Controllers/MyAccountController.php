<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Order;
use App\Models\User;
use App\Services\GatewayService;
use App\Services\OnScreenNotificationService;
use App\Services\PayeezyService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class MyAccountController extends Controller
{
    /**
     * My Account page
     *
     * @return RedirectResponse|Illuminate\Http\Response
     */
    public function index(Request $request, string $expandedSection = null)
    {
        if ($expandedSection === 'verified') {
            return redirect()
                ->route('my-account.index')
                ->withInput($request->all())
                ->with(OnScreenNotificationService::with([
                    'message' => 'Your email has been verified.',
                    'level' => 'success',
                ]));
        } elseif ($expandedSection === 'reactivated') {
            return redirect()
                ->route('my-account.index')
                ->withInput($request->all())
                ->with(OnScreenNotificationService::with([
                    'message' => 'Your Brown Bear Digital Account has been activated. Welcome back!',
                    'level' => 'success',
                ]));
        } elseif ($expandedSection === 'password-reset') {
            return redirect()
                ->route('my-account.index')
                ->withInput($request->all())
                ->with(OnScreenNotificationService::with([
                    'message' => 'Your password has been changed.',
                    'level' => 'success',
                ]));
        }

        $user = Auth::user();
        $orders = self::getMyOrders();
        $memberships = Membership::getForCurrentUser();
        $homeCarWash = $user->home_location;
        $paymentMethod = $user->wash_connect->payment_method ?? null;

        return parent::view('my-account.index', compact([
            'expandedSection',
            'homeCarWash',
            'memberships',
            'orders',
            'paymentMethod',
            'user',
        ]), [ 'headers' => [ 'Cache-Control' => 'must-revalidate, no-store, no-cache, private' ] ]); // https://bitbucket.org/flowerpress/brownbear.com/issues/694/account-login-identity-switching-with-use
    }

    /**
     * Returns orders of the logged in user
     */
    private static function getMyOrders()
    {
        $user = Auth::user();

        $orders = Order::with('products')
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query
                    ->where('merch_status', null)
                    ->orWhere(function ($query) {
                        $query
                            ->where('merch_status', '!=', Order::STATUS_UNPAID)
                            ->where('merch_status', '!=', Order::STATUS_FAILED_PAYMENT);
                    });
            })
            ->where(function ($query) {
                $query
                    ->where('club_status', null)
                    ->orWhere(function ($query) {
                        $query
                            ->where('club_status', '!=', Order::STATUS_UNPAID)
                            ->where('club_status', '!=', Order::STATUS_FAILED_PAYMENT);
                    });
            })
            ->orderby('created_at', 'desc')
            ->get();

        $orders = $orders->map(function ($order) {
            return $order->updateClubStatusFromGateway();
        });

        return $orders;
    }

    /**
     * Edit Contact Info & Shipping Address page
     */
    public function editContactInfoAndShippingAddress(Request $request)
    {
        $user = Auth::user();

        return parent::view('my-account.edit-contact-info-shipping-address', compact('user'));
    }

    /**
     * Update Contact Info & Shipping Address page
     */
    public function updateContactInfoAndShippingAddress(Request $request): RedirectResponse
    {
        $validator = self::validateContactInfoAndShippingAddress($request);

        if ($validator->fails()) {
            return self::handleFailedValidation($validator, $request);
        }

        // Verify email is unique in local database
        $existingUser = User::where('email', $request->get('email'))->first();
        if ($existingUser && $existingUser->id !== Auth::user()->id) {
            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors([ 'email' => 'The email you entered is already taken.' ]);
        }

        $response = GatewayService::updateUser([
            'first_name' => $request->get('customer_first_name'),
            'last_name' => $request->get('customer_last_name'),
            'email' => $request->get('email'),
            'shipping_first_name' => $request->get('first_name'),
            'shipping_last_name' => $request->get('last_name'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'zip' => $request->get('zip'),
            'phone' => $request->get('phone'),
        ]);
        if (!$response->success) {
            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors($response->errors ?? null)
                ->with(OnScreenNotificationService::with([
                    'message' => 'Were were unable to save your information.',
                    'additionalInfo' => $response->error,
                    'level' => 'error',
                ]));
        }

        $user = Auth::user();
        $user->email = $request->get('email');
        $user->save();

        return redirect()
            ->route('my-account.index', ['expandedSection' => 'contact-info-shipping-address'])
            ->with(OnScreenNotificationService::with([
                'message' => 'Your contact info & shipping address have been saved.',
                'level' => 'success',
            ]));
    }

    /**
     * Validate request for updateContactInfoAndShippingAddress
     */
    private static function validateContactInfoAndShippingAddress(Request $request): \Illuminate\Validation\Validator
    {
        $rules = [
            'email' => 'required|email:filter,rfc,dns',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required|min:5|max:10',
            'phone' => 'required',
        ];

        return self::validateRequest($request, $rules);
    }

    /**
     * Edit Home Car Wash screen
     */
    public function editHomeCarWash()
    {
        $homeCarWash = Auth::user()->home_location;

        return view('my-account.edit-home-car-wash', compact('homeCarWash'));
    }

    /**
     * Save selected Home Car Wash
     */
    public function storeHomeCarWash(Request $request): RedirectResponse
    {
        $rules = [ 'location_id' => 'required|exists:locations,id' ];
        $messages = [ 'location_id.required' => 'You must select a car wash location.' ];
        $validator = self::validateRequest($request, $rules, $messages);
        if ($validator->fails()) {
            return self::handleFailedValidation($validator, $request);
        }

        $user = Auth::user();
        $user->home_location_id = $request->get('location_id');
        $user->save();

        return redirect()
            ->route('my-account.index', ['expandedSection' => 'home-car-wash'])
            ->with(OnScreenNotificationService::with([
                'message' => 'Your home car wash has been saved.',
                'level' => 'success',
            ]));
    }

    /**
     * Edit Notification Preferences page
     */
    public function editNotificationPreferences(Request $request)
    {
        $user = Auth::user();

        return parent::view('my-account.edit-notification-preferences', compact('user'));
    }

    /**
     * Update Notification Preferences page
     */
    public function updateNotificationPreferences(Request $request): RedirectResponse
    {
        $validator = self::validateNotificationPreferences($request);

        if ($validator->fails()) {
            return self::handleFailedValidation($validator, $request);
        }

        $user = Auth::user();
        $user->fill([
            'notification_pref_orders' => $request->get('orders'),
            // 'notification_pref_promotions' => $request->get('promotions'), ** disabled as per https://bitbucket.org/flowerpress/brownbear.com/issues/317/hide-irrelevant-notification-settings
            // 'notification_pref_marketing' => $request->get('marketing'), ** disabled as per https://bitbucket.org/flowerpress/brownbear.com/issues/317/hide-irrelevant-notification-settings
        ]);
        $user->save();

        return redirect()
            ->route('my-account.index', ['expandedSection' => 'notification-preferences'])
            ->with(OnScreenNotificationService::with([
                'message' => 'Your notification preferences have been saved.',
                'level' => 'success',
            ]));
    }

    /**
     * Validate request for updateNotificationPreferences
     */
    private static function validateNotificationPreferences(Request $request): \Illuminate\Validation\Validator
    {
        $rules = [
            'orders' => 'required',
            // 'promotions' => 'required', ** disabled as per https://bitbucket.org/flowerpress/brownbear.com/issues/317/hide-irrelevant-notification-settings
            // 'marketing' => 'required', ** disabled as per https://bitbucket.org/flowerpress/brownbear.com/issues/317/hide-irrelevant-notification-settings
        ];

        return self::validateRequest($request, $rules);
    }

    /**
     * Edit Password page
     */
    public function editPassword(Request $request)
    {
        $user = Auth::user();

        return parent::view('my-account.edit-password', compact('user'));
    }

    /**
     * Update Password page
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $rules = [ 'password' => [ 'confirmed' ] ];
        $validator = self::validateRequest($request, $rules);
        if ($validator->fails()) {
            return self::handleFailedValidation($validator, $request);
        }

        try {
            GatewayService::patch('/user', $request->only('current_password', 'password'), [ 'throw_exception' => true ]);
        } catch (Exception $e) {
            $response = (object)json_decode($e->getResponse()->getBody()->getContents());

            return redirect()
                ->route('my-account.password.edit')
                ->withInput()
                ->withErrors($response->errors ?? []);
        }

        return redirect()
            ->route('my-account.index', ['expandedSection' => 'password'])
            ->with(OnScreenNotificationService::with([
                'message' => 'Your password has been saved.',
                'level' => 'success',
            ]));
    }

    /**
     * Show payment methods screen
     */
    public function showPaymentMethods(): View
    {
        $user = Auth::user();
        $hasWashConnectAccount = !!count($user->wash_connect->customers ?? []);

        $paymentMethod = $user->wash_connect->payment_method ?? null;

        return view('my-account.payment-methods.show', compact('hasWashConnectAccount', 'paymentMethod'));
    }

    /**
     * Show payment method entry form
     *
     * @return Illuminate\View\View|Illuminate\Http\RedirectResponse
     */
    public function editPaymentMethod()
    {
        $user = Auth::user();

        $paymentForm = PayeezyService::paymentForm([
            'amount' => 0,
            'customerFirstName' => $user->first_name,
            'customerId' => $user->id,
            'customerLastName' => $user->last_name,
            'description' => 'Updating payment method on brownbear.com',
            'responseUrl' => route('my-account.payment-methods.edit'),
            'transactionType' => 'AUTH_ONLY',
            'zip' => $user->billing_zip ?? $user->zip,
        ]);

        return view('my-account.payment-methods.edit', compact('paymentForm'));
    }

    /**
     * Save payment method
     */
    public function updatePaymentMethod(Request $request): RedirectResponse
    {
        $response = PayeezyService::parsePaymentFormResponse($request);

        if (!$response->success) {
            $status = $response->message ?? 'Unable to process your payment method.';
            $additionalInfo = $response->bank_message ?? null;

            return redirect()
                ->route('my-account.payment-methods.show')
                ->with(OnScreenNotificationService::with([
                    'message' => $status,
                    'additionalInfo' => $additionalInfo,
                    'level' => 'error',
                ]));
        }

        $paymentMethod = PayeezyService::getPaymentMethodFromPaymentFormResponse($request);

        $response = GatewayService::setPaymentMethod($paymentMethod);

        if (!$response->success) {
            return redirect()
                ->route('my-account.payment-methods.show')
                ->with(OnScreenNotificationService::with([
                    'message' => $response->message,
                    'level' => 'error',
                ]));
        }

        return redirect()
            ->route('my-account.payment-methods.show')
            ->with(OnScreenNotificationService::with([
                'message' => 'Your payment method has been changed.',
                'level' => 'success',
            ]));
    }

    /**
     * Cancel Account page
     */
    public function cancelAccount(Request $request)
    {
        $user = Auth::user();

        return parent::view('my-account.cancel-account', compact('user'));
    }

    /**
     * Destroy Account page
     */
    public function destroyAccount(Request $request): RedirectResponse
    {
        try {
            GatewayService::delete('/user', [ 'throw_exception' => true ]);
        } catch (Exception $e) {
            return redirect()
                ->route('my-account.account.cancel')
                ->withInput()
                ->with(OnScreenNotificationService::with([
                    'message' => 'There was a problem canceling your account. Please try again.',
                    'level' => 'error',
                ]));
        }

        $user = Auth::user();

        // Customer email
        $name = $user->first_name;
        $email = $user->email;
        $host = $request->server('HTTP_HOST');
        $content = 'We received your request to cancel your Brown Bear Digital account. We are sorry to see you go. If you have a Wash Club membership you would like to cancel, please call us or contact support. If you have any feedback regarding how we can improve our service, please get in touch.';
        $content = [
            'html' => $content,
            'text' => $content,
        ];
        try {
            Mail::send(
                [
                    'html' => 'emails.general-html',
                    'text' => 'emails.general-text',
                ],
                [
                    'user' => $user,
                    'name' => $name,
                    'email' => $email,
                    'content' => $content,
                    'host' => $host,
                ],
                function ($message) use ($email, $name) {
                    $message->to($email, $name)
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject('Brown Bear Digital Account Cancellation Notice');
                }
            );
        } catch (Exception $e) {
            Log::error('Unable to send customer cancellation email to '.$email.':');
            Log::error($e);
        }

        // Admin email
        $name = config('mail.support.name');
        $email = config('mail.support.address');
        $host = $request->server('HTTP_HOST');
        $content = [
            'html' => '<p>User '.$user->full_name.' has canceled his/her account. Comments entered during cancellation appear below.</p>'
                .'<p>'.($request->comments ? '<pre>'.strip_tags($request->comments).'</pre>' : '<i>No comments entered</i>').'</p>'
                ."<p>In case you wish to follow up, the user's email address is ".$user->email.".</p>",
            'text' => 'User '.$user->full_name.' has canceled his/her account. Comments entered during cancellation appear below.'."\n"
                ."\n"
                .($request->comments ?? 'No comments entered')."\n"
                ."\n"
                ."In case you wish to follow up, the user's email address is ".$user->email.".",
        ];
        try {
            Mail::send(
                [
                    'html' => 'emails.general-html',
                    'text' => 'emails.general-text',
                ],
                [
                    'user' => null,
                    'name' => $name,
                    'email' => $email,
                    'content' => $content,
                    'host' => $host,
                    'isInternal' => true,
                ],
                function ($message) use ($email, $name) {
                    $message->to($email, $name)
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject('Brown Bear Digital Account Cancellation Notice');
                }
            );
        } catch (Exception $e) {
            Log::error('Unable to send admin customer cancellation email to '.$email.':');
            Log::error($e);
        }

        return GatewayService::logoutRedirect([
            'notification-message' => 'Your account has been canceled.',
            'notification-level' => 'success',
        ]);
    }

    /**
     * Manage Account page
     */
    public function manageAccount(Request $request)
    {
        $user = Auth::user();

        return parent::view('my-account.manage-account', compact('user'));
    }
}
