<?php

namespace App\Services;

use App\Facades\Cart;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GatewaySsoService
{
    /**
     * Return an encoded string that represents a redirect URL to be performed by Gateway after Gateway performs some action
     * Often included in URLs like "?redirectAfterLogin=".redirectRoute('some.named.route')
     */
    public static function redirectRoute(string $routeName, ?array $routeParams = null): string
    {
        return self::redirectUrl(URL::route($routeName, $routeParams, false));
    }

    /**
     * Return an encoded string that represents a redirect URL to be performed by Gateway after Gateway performs some action
     * Often included in URLs like "?redirectTo=".redirectUrl('/some/url/path')
     */
    public static function redirectUrl(string $path): string
    {
        $url = urlencode('{website}'.$path);

        return $url;
    }

    /**
     * Redirect to OAuth server to establish link
     * After redirect, visitor will execute GatewayService::login() below
     */
    public static function ssoRedirect(): RedirectResponse
    {
        $socialite = self::socialite();

        return $socialite->redirect();
    }

    /**
     * Log in user
     */
    public static function login(): ?User
    {
        if (!$ssoUser = self::getSsoCallbackUser()) {
            return null;
        }

        if ($user = User::where('email', $ssoUser->email)->first()) {
            $user->update([ 'email' => $ssoUser->email ]);
        } else {
            $user = \App\Http\Controllers\Auth\RegisterController::create([ 'email' => $ssoUser->email ]);
        }

        Auth::login($user, true);

        // Save OAuth user to session
        self::setUser($ssoUser);

        return $user;
    }

    /**
     * Return OAuth user
     * MUST BE DURING OAUTH CALLBACK SEQUENCE! Otherwise it throws an InvalidStateException
     */
    public static function getSsoCallbackUser(): ?SocialiteUser
    {
        $socialite = self::socialite();

        try {
            return $socialite->user();
        } catch (InvalidStateException $e) {
            return null;
        }
    }

    /**
     * Return Socialite Provider that communicates with Gateway OAUth server
     */
    public static function socialite(): Provider
    {
        return Socialite::driver('laravelpassport');
    }

    /**
     * Save the OAuth user to session
     *
     * @param SocialiteUser|object $user
     */
    public static function setUser($user): void
    {
        $user = self::convertSocialiteUserToSessionUser($user);

        Session::put('gateway:user', $user);
    }

    /**
     * Return curated user data from user returned by Socialite
     *
     * @param SocialiteUser|object $user
     */
    private static function convertSocialiteUserToSessionUser($user): ?object
    {
        if (!$user) {
            return null;
        }

        $newUser = collect(Session::get('gateway:user'));

        if ($user instanceof SocialiteUser) {
            $newUser = $newUser
                ->merge([
                    'id' => $user->id,
                    'token' => $user->token,
                    'avatar' => $user->avatar,
                ])
                ->merge($user->user);
        } else {
            $newUser = $newUser->merge($user);
        }

        $newUser = (object)$newUser->toArray();

        return $newUser;
    }

    /**
     * Return the OAuth user
     */
    public static function user(?bool $forceFetch = false): ?object
    {
        if (!Auth::check()) {
            return null;
        }

        if ($forceFetch || !$user = Session::get('gateway:user')) {
            $user = GatewayService::get('/user') ?? null;
            if ($user === null && class_exists('SocialiteUser')) {
                $user = new SocialiteUser();
            }

            self::setUser($user);
        }

        return $user;
    }

    /**
     * Update the SSO user
     */
    public static function updateUser(array $data): object
    {
        try {
            $response = GatewayService::patch(
                '/user',
                $data,
                [ 'throw_exception' => true ]
            );
        } catch (ClientException $e) {
            $error = json_decode($e->getResponse()->getBody()->getContents() ?? null);
        }

        if ($response ?? null) {
            $success = true;

            self::setUser($response->data);
        }

        return (object)[
            'success' => $success ?? false,
            'error' => $error->message ?? null,
            'errors' => $error->errors ?? null,
        ];
    }

    /**
     * Check whether the user's Gateway session is expired
     * Automatically log them out if expired
     */
    public static function checkSessionExpiration(): void
    {
        if (self::isSessionExpired()) {
            self::sessionExpired();
        }
    }

    /**
     * Return bool whether the user's Gateway session is expired
     */
    public static function isSessionExpired(): bool
    {
        if (Session::get('user.remember_me')) {
            return false;
        }

        return GatewayService::get('/user/is-expired', [ 'handle_exception' => false ])->data ?? true;
    }

    /**
     * Gateway session is expired, force logout everywhere
     */
    public static function sessionExpired(): void
    {
        GatewayService::forgetAppToken();

        $redirect = GatewayService::logoutRedirect([
            'notification-message' => 'Your session has expired. Please sign in and try again.',
            'notification-level' => 'error',
        ]);

        $redirect->send();
    }

    /**
     * Completely log the user out of the local interface as well as the SSO provider
     */
    public static function logoutRedirect(?array $message = null): RedirectResponse
    {
        Cart::destroy();

        GatewayService::delete('/user/token', [ 'handle_exception' => false ]);

        self::setUser(null);

        Auth::logout();

        if ($message && ($message['notification-message'] ?? null)) {
            OnScreenNotificationService::flash([
                'message' => $message['notification-message'],
                'level' => $message['notification-level'] ?? null,
            ]);

            // On local environments forceBrowserSync.js will perform a second redirect, therefore wiping the
            // flashed status message above. To counteract this, force the browser to output something.
            if (config('app.env') === 'local') {
                dump('');
                dump('THIS MESSAGE WILL ONLY APPEAR ON LOCAL');
            }
        }

        $url = Config::get('services.gateway.base_url').Config::get('services.gateway.oauth_logout_url').'?redirectAfterLogout='.self::redirectRoute('sso.after-logout');

        return Redirect::to($url);
    }
}
