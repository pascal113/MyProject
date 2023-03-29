<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GatewayService;
use FPCS\FlexiblePageCms\Models\CmsPage;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

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
    protected function redirectTo()
    {
        if (request()->get('redirectTo')) {
            return '/'.preg_replace('/^\//', '', request()->get('redirectTo'));
        }

        return route('my-account.index');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        if ($errors = ($request->get('errors') ?? null)) {
            $inputs = $request->get('inputs') ?? [];

            return redirect()->route('password.reset', [ 'token' => $token ])
                ->withErrors($errors)
                ->withInput($inputs);
        }

        $askAQuestion = CmsPage::getContentFromPage('/', 'askAQuestion');

        $meta = parent::meta();

        return view('auth.passwords.reset')->with([
            'meta' => $meta,
            'token' => $token,
            'email' => $request->email,
            'askAQuestion' => $askAQuestion,
        ]);
    }

    /**
     * Show success screen
     */
    public function showSuccess(Request $request): RedirectResponse
    {
        Session::flash('redirectTo', route('my-account.index', [ 'expandedSection' => 'password-reset' ]));

        return GatewayService::ssoRedirect();
    }
}
