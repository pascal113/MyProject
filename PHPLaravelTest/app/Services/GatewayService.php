<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class GatewayService extends GatewaySsoService
{
    /**
     * Get all vehicles stored in Wash Connect for the current user
     */
    public static function getVehiclesForCurrentUser(?array $filters = []): array
    {
        if (!Auth::user()) {
            return [];
        }

        $query = !empty($filters) ? '?'.Arr::query([ 'filters' => $filters ]) : '';

        return self::get('/v2/user/vehicles'.$query)->data ?? [];
    }

    /**
     * Set the stored payment method
     */
    public static function setPaymentMethod(object $paymentMethod): object
    {
        try {
            self::put('/v2/user/payment-method', (array)$paymentMethod, [ 'throw_exception' => true ]);
        } catch (GuzzleException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());

            $message = array_values((array)($response->errors ?? []))[0][0] ?? $response->message ?? 'Unable to save payment method.';

            return (object)[
                'success' => false,
                'message' => $message,
            ];
        }

        return (object)[ 'success' => true ];
    }

    /**
     * Fetches current Terms from gateway
     */
    public static function getCurrentTermsContent(): ?array
    {
        $terms = self::get('/v2/terms-content/current');

        return $terms->data->content ?? null;
    }

    /**
     * GET from the Gateway REST API
     *
     * @throws GuzzleException
     */
    public static function get(string $path, ?array $options = [], ?bool $forceFetch = false): ?object
    {
        if (!$forceFetch && $cached = self::getFromCache($path)) {
            return $cached;
        }

        $url = self::url($path);

        try {
            $resp = self::client()->request(
                $options['method'] ?? 'GET',
                $url,
                [ 'headers' => self::headers($options) ]
            );
        } catch (GuzzleException $e) {
            if ($options['handle_exception'] ?? true) {
                self::handleException($e);
            }

            if ($options['throw_exception'] ?? false) {
                throw $e;
            }

            return null;
        }

        $data = json_decode($resp->getBody()->getContents());

        self::saveToCache($path, $data);

        return $data;
    }

    /**
     * POST to the Gateway REST API
     *
     * @throws GuzzleException
     */
    public static function post(string $path, array $data, ?array $options = []): ?object
    {
        $url = self::url($path);

        try {
            $resp = self::client()->request(
                $options['method'] ?? 'POST',
                $url,
                [
                    'headers' => self::headers($options),
                    'form_params' => $data,
                ]
            );
        } catch (GuzzleException $e) {
            if ($options['handle_exception'] ?? true) {
                self::handleException($e);
            }

            if ($options['throw_exception'] ?? false) {
                throw $e;
            }

            return null;
        }

        return json_decode($resp->getBody()->getContents());
    }

    /**
     * PATCH to the Gateway REST API
     */
    public static function patch(string $path, array $data, ?array $options = []): ?object
    {
        $options['method'] = 'PATCH';

        return self::post($path, $data, $options);
    }

    /**
     * PUT to the Gateway REST API
     */
    public static function put(string $path, array $data, ?array $options = []): ?object
    {
        $options['method'] = 'PUT';

        return self::post($path, $data, $options);
    }

    /**
     * DELETE to the Gateway REST API
     */
    public static function delete(string $path, ?array $options = []): ?object
    {
        $options['method'] = 'DELETE';

        return self::get($path, $options);
    }

    /**
     * Instantiate Guzzle Client
     */
    private static function client(): Client
    {
        return new Client();
    }

    /**
     * Generate a complete Gateway REST API URL, given a resource path
     */
    public static function url(string $path): string
    {
        return config('services.gateway.base_url').'/api/'.preg_replace('/^\/*/', '', $path);
    }

    /**
     * Retrieve data from Cache
     */
    private static function getFromCache(string $path): ?object
    {
        $cacheKey = self::cacheKey($path);

        return Config::get($cacheKey) ? (object)Config::get($cacheKey) : null;
    }

    /**
     * Save array of Users to cache, as if they were fetched individually
     */
    public static function saveUsersToCache(?object $response = null): void
    {
        if (!$response) {
            return;
        }

        foreach ($response->data ?? [] as $user) {
            self::saveToCache('/v2/users/'.$user->email, (object)array_merge(
                (array)$response,
                [ 'data' => $user ],
            ));
        }
    }

    /**
     * Save data to cache
     */
    private static function saveToCache(string $path, ?object $data = null): void
    {
        $cacheKey = self::cacheKey($path);

        Config::set($cacheKey, (array)$data);
    }

    /**
     * Get key used to store this request data in cache
     */
    private static function cacheKey(string $path): string
    {
        $path = preg_replace('/^[\/]*/', '', $path);

        return 'gatewaycache.'.$path;
    }

    /**
     * Compile and return headers for API request to Gateway
     */
    private static function headers(?array $options = []): array
    {
        $headers = [];

        if ($options['useAppToken'] ?? false) {
            $token = self::getAppToken();

            $headers['Authorization'] = 'Basic '.base64_encode(config('services.gateway.app_key').':'.$token);
        } elseif ($oauthToken = self::oauthToken()) {
            $headers['Authorization'] = 'Bearer '.$oauthToken;
        }

        $headers = array_merge([ 'Accept' => 'application/json' ], $headers);

        return $headers;
    }

    /**
     * Use app-token stored in session, or request and return a new one
     */
    private static function getAppToken(): ?string
    {
        if ($token = Session::get('gateway:app-token')) {
            return $token;
        }

        $key = config('services.gateway.app_key');
        $timestamp = \Carbon\Carbon::now();
        $isoString = $timestamp->toISOString();
        $hash = sha1(sha1(config('services.gateway.app_secret')).$timestamp->addHour()->timestamp);

        $token = self::post('v2/app-tokens', [
            'key' => $key,
            'hash' => $hash,
            'utc_timestamp' => $isoString,
        ]);

        if (!$token) {
            self::forgetAppToken();

            return null;
        }

        Session::put('gateway:app-token', $token->data->token);

        return $token->data->token;
    }

    /**
     * Wipe the currently-saved app token from session
     */
    public static function forgetAppToken(): void
    {
        Session::forget('gateway:app-token');
    }

    /**
     * Return the OAuth access token
     */
    public static function oauthToken(): ?string
    {
        $user = self::user();

        return $user->token ?? null;
    }

    /**
     * Handle exceptions returned by Gateway
     */
    private static function handleException(GuzzleException $e): void
    {
        if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
            self::sessionExpired();
        }
    }
}
