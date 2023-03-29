<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Validate a request based on rules.
     */
    protected static function validateRequest(Request $request, array $rules, ?array $messages = [], ?array $fieldNames = []): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($request->all(), $rules, $messages, $fieldNames);

        return $validator;
    }

    /**
     * Handle failed validation, return proper response.
     */
    protected static function handleFailedValidation(\Illuminate\Validation\Validator $validator, Request $request)
    {
        $errors = $validator->errors();

        // Output error response
        return redirect()
            ->back()
            ->withInput($request->all())
            ->withErrors($errors);
    }

    /**
     * Render a view, including meta & schema.org data
     */
    protected static function view(string $view, ?array $data = [], ?array $options = []): Response
    {
        $data['meta'] = self::meta((object)($data['meta'] ?? []));

        $response = response()->view($view, $data);

        foreach ($options['headers'] ?? [] as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }

    /**
     * Return complete meta data including defaults for anything not explicitly passed
     */
    public static function meta(?object $meta = null): object
    {
        $meta = $meta ?? (object)[];

        // Default meta data
        $meta = (object)collect((object)[
            'title' => config('flexible-page-cms.default_page_meta_title'),
            'description' => config('flexible-page-cms.default_page_meta_description'),
            'keywords' => config('flexible-page-cms.default_page_meta_keywords'),
        ])
            ->merge(collect($meta ?? [])->filter(function ($value) {
                return !!$value;
            }))
            ->toArray();

        // Default schema.org data
        $meta->schemaOrg = collect((object)[
            '@context' => 'https://schema.org',
            '@type' => 'AutoWash',
            'url' => url()->current(),
            'name' => 'Brown Bear Car Wash',
            'image' => [
                url('/img/logo-og.jpg'),
            ],
            'logo' => url('/img/logo-og.jpg'),
            'telephone' => '206.789.3700',
            'address' => (object)[
                '@type' => 'PostalAddress',
                'streetAddress' => '3977 Leary Way NW',
                'addressLocality' => 'Seattle',
                'addressRegion' => 'WA',
                'postalCode' => '98107',
                'addressCountry' => 'US',
            ],
            'priceRange' => '$10-16',
        ])
            ->merge($meta->schemaOrg ?? [])
            ->toArray();

        return $meta;
    }

    /**
     * Return bool whether an error response has been detected from Gateway
     */
    protected static function isErrorResponseFromGateway(Request $request): bool
    {
        if ($request->get('errors') ?? null) {
            return true;
        }

        return false;
    }

    /**
     * Handle an error response received from Gateway
     */
    protected static function handleErrorResponseFromGateway(Request $request, string $redirectTo): RedirectResponse
    {
        if ($errors = $request->get('errors') ?? null) {
            $inputs = $request->get('inputs') ?? [];

            return Redirect::to($redirectTo)
                ->withErrors($errors)
                ->withInput($inputs);
        }
    }

    /**
     * Return an HTTP abort response for either HTML or JSON
     *
     * @param array|string $payload
     */
    protected static function abort(int $statusCode = 200, $payload = []): JsonResponse
    {
        if (is_string($payload)) {
            $payload = [ 'message' => $payload ];
        }

        $message = null;
        if (!isset($payload['message'])) {
            if ($statusCode === 200) {
                $message = 'OK';
            } elseif ($statusCode === 400) {
                $message = 'Bad Request';
            } elseif ($statusCode === 401) {
                $message = 'Unauthorized';
            } elseif ($statusCode === 403) {
                $resourceString = !empty($payload['resourceType']) ? ' '.$payload['resourceType'] : '';
                $message = sprintf('You do not have access to view this%s.'
                    .' Are you sure you are logged in to the correct account?', $resourceString);
            } elseif ($statusCode === 404) {
                $message = 'Not Found';
            } elseif ($statusCode === 500) {
                $message = 'Internal Server Error';
            }

            if ($message && !isset($payload['message'])) {
                $payload['message'] = $message;
            }
        }
        if (request()->wantsJson()) {
            return (new JsonResponse($payload))->setStatusCode($statusCode);
        }

        if ($statusCode !== 200) {
            abort($statusCode, $payload['message']);
        }
    }
}
