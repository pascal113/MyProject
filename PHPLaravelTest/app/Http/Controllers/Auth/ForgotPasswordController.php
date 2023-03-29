<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OnScreenNotificationService;
use FPCS\FlexiblePageCms\Models\CmsPage;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $askAQuestion = CmsPage::getContentFromPage('/', 'askAQuestion');

        $meta = parent::meta();

        return view('auth.passwords.email', compact('meta', 'askAQuestion'));
    }

    /**
     * After the user attempts to reset password on the SSO server they are redirected here
     */
    public static function ssoAfterForgotPassword(Request $request)
    {
        if (self::isErrorResponseFromGateway($request)) {
            return self::handleErrorResponseFromGateway($request, route('password.request'));
        }

        OnScreenNotificationService::flash([ 'message' => Lang::get('passwords.sent') ]);

        return Redirect::route('password.request');
    }
}
