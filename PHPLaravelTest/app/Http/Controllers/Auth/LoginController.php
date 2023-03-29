<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GatewayService;
use App\Services\OnScreenNotificationService;
use FPCS\FlexiblePageCms\Models\CmsPage;
use FPCS\FlexiblePageCms\Services\CmsRoute;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Where to redirect users after login.
     */
    protected static function redirectTo()
    {
        if (request()->get('redirectTo')) {
            return '/'.preg_replace('/^\//', '', request()->get('redirectTo'));
        }

        return CmsRoute::get('/');
    }

    /**
     * After a user is authenticated
     */
    protected static function authenticated(Request $request, User $user): void
    {
        Session::flash('isNewlyLoggedIn', true);

        $restoreSavedCart = Session::get('restoreSavedCart') === '0' ? false : true;
        if ($restoreSavedCart) {
            // Restore shopping cart from database (merge into current cart)
            $identifier = 'userId:'.$user->id.':default';
            Cart::merge($identifier, false);

            // Since we do not retain discounts when merging the cart in from the database (2nd parameter above, false), remove references to coupon codes stored on cart items
            foreach (Cart::content() as $row) {
                Cart::update($row->rowId, [ 'options' => $row->options ]);
            }
        }
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $askAQuestion = CmsPage::getContentFromPage('/', 'askAQuestion');

        $meta = parent::meta();

        return view('auth.login', compact('meta', 'askAQuestion'));
    }

    /**
     * After the user attemps log in on the SSO server they are redirected here
     */
    public static function ssoAfterLogin(Request $request): RedirectResponse
    {
        if ($errors = ($request->get('errors') ?? null)) {
            $inputs = $request->get('inputs') ?? [];

            if ($errors['user_is_legacy'] ?? false) {
                $route = route('reactivate.index', [ 'email' => $inputs['email'], 'mode' => 'legacy' ]);
            }
            if ($errors['user_is_deleted'] ?? false) {
                $route = route('reactivate.index', [ 'email' => $inputs['email'] ]);
            }

            return Redirect::to($route ?? route('login'))
                ->withErrors($errors)
                ->withInput($inputs);
        }

        Session::put('user.remember_me', $request->filled('rememberMe'));

        if ($request->has('restoreSavedCart')) {
            Session::flash('restoreSavedCart', $request->get('restoreSavedCart'));
        }

        return GatewayService::ssoRedirect();
    }

    /**
     * OAuth Callback receiver. After the user successfully completes SSO linking
     */
    public static function ssoCallback(Request $request): RedirectResponse
    {
        if (!$user = GatewayService::login()) {
            return Redirect::route('login')
                ->with(OnScreenNotificationService::with([
                    'message' => 'An internal error has occurred, please try again.',
                    'level' => 'error',
                ]));
        }

        self::authenticated($request, $user);

        $redirectTo = Session::get('redirectTo') ?? self::redirectTo();
        Session::forget('redirectTo');

        return Redirect::to($redirectTo);
    }

    /**
     * Log out the user
     */
    public static function logout(Request $request): RedirectResponse
    {
        if ($request->has('onlyWeb') && $request->has('redirectTo')) {
            Auth::logout();

            return redirect()->to($request->get('redirectTo'));
        }

        return GatewayService::logoutRedirect();
    }

    /**
     * After the user successfully logs out of SSO
     */
    public static function ssoAfterLogout(): RedirectResponse
    {
        Session::reflash();

        return Redirect::route('login');
    }
}
