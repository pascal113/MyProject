<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    /**
     * Handle failed validation, return proper response.
     */
    protected static function handleFailedValidation(\Illuminate\Validation\Validator $validator, Request $request): JsonResponse
    {
        $errors = $validator->errors();

        // Output error response
        return Response::json(
            [
                'message' => 'Validation Failed',
                'errors' => $errors,
                'request' => config('app.debug') ? $request->all() : null,
            ]
        )->setStatusCode(400);
    }
}
