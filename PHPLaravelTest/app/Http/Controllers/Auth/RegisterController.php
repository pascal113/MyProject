<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\GatewayService;
use FPCS\FlexiblePageCms\Models\CmsPage;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Where to redirect users after registration.
     */
    protected static function redirectTo()
    {
        if (request()->get('redirectTo')) {
            return '/'.preg_replace('/^\//', '', request()->get('redirectTo'));
        }

        return route('my-account.index');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $askAQuestion = CmsPage::getContentFromPage('/', 'askAQuestion');

        $meta = parent::meta();

        return view('auth.register', compact('meta', 'askAQuestion'));
    }

    /**
     * After the user attempts registration during checkout flow they are redirected here by the SSO server
     */
    public static function ssoAfterCheckoutRegister(Request $request)
    {
        $redirectTo = route('checkout.account').'#register';

        Session::put('redirectTo', $redirectTo);

        return self::ssoAfterRegister($request, $redirectTo);
    }

    /**
     * After the user attempts registration after successfully placing an order they are redirected here by the SSO server
     */
    public static function ssoAfterOrderSuccessRegister(Request $request, string $orderId, ?string $accessToken = null)
    {
        $redirectTo = route('checkout.registration-success', [ $orderId, $accessToken ]);

        Session::put('redirectTo', $redirectTo);

        return self::ssoAfterRegister($request, $redirectTo);
    }

    /**
     * After the user attemps registration they are redirected here by the SSO server
     */
    public static function ssoAfterRegister(Request $request, ?string $redirectTo = null)
    {
        if (self::isErrorResponseFromGateway($request)) {
            if ($request->input('errors.user_is_legacy')) {
                $errors = $request->get('errors') ?? null;
                $inputs = $request->get('inputs') ?? [];

                $route = route('reactivate.index', [ 'email' => $inputs['email'], 'mode' => 'legacy' ]);

                return Redirect::to($route ?? route('login'))
                    ->withErrors($errors)
                    ->withInput($inputs);
            }

            return self::handleErrorResponseFromGateway($request, $redirectTo ?? route('register'));
        }

        return GatewayService::ssoRedirect();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return User::validator($data);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    public static function create(array $data)
    {
        Session::flash('isNewlyRegistered', true);

        $role = Role::where('name', 'customer')->first();

        return User::create([
            'role_id' => $role->id ?? null,
            'email' => $data['email'],
            'notification_pref_orders' => true,
            'notification_pref_promotions' => true,
            'notification_pref_marketing' => true,
        ]);
    }
}
