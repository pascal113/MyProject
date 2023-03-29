<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Return User by email, for consumption by the gateway
     * Guarded by ApiTrustedClients middleware
     */
    public static function show(string $email): JsonResponse
    {
        if (!$user = User::where('email', $email)->first()) {
            return Response::json([ 'message' => 'Not Found' ])->setStatusCode(404);
        }

        $user->role; // Attach Role data by hitting accessor

        return Response::json([ 'data' => $user ])->setStatusCode(200);
    }

    /**
     * Return array of all permissions for requested user, for consumption by the gateway
     * Guarded by ApiTrustedClients middleware
     */
    public static function getUserPermissions(string $email): JsonResponse
    {
        if (!$user = User::where('email', $email)->first()) {
            return Response::json([ 'message' => 'Not Found' ])->setStatusCode(404);
        }

        return Response::json([ 'data' => $user->allPermissions ])->setStatusCode(200);
    }

    /**
     * Update a User by email, for consumption by the gateway
     * Guarded by ApiTrustedClients middleware
     */
    public static function update(Request $request, string $email): JsonResponse
    {
        if (!$user = User::where('email', $email)->first()) {
            $user = User::create([ 'email' => $email ]);
        }

        $updates = [];

        if ($request->has('role_id')) {
            $roleId = $request->get('role_id', null);
            if ($roleId && !Role::find($roleId)) {
                return Response::json([ 'message' => 'Not Found' ])->setStatusCode(404);
            }

            $updates['role_id'] = $roleId;
        }

        foreach ([ 'orders', 'promotions', 'marketing' ] as $field) {
            if ($request->has('notification_pref_'.$field)) {
                $updates['notification_pref_'.$field] = $request->get('notification_pref_'.$field);
            }
        }

        $user->update($updates);

        return Response::json([ 'message' => 'Updated' ])->setStatusCode(200);
    }

    /**
     * Delete a user by emaol, for use by the gateway
     * Guarded by ApiTrustedClients middleware
     */
    public static function destroy(string $email): JsonResponse
    {
        if (!$user = User::where('email', $email)->first()) {
            return Response::json([ 'message' => 'Not Found' ])->setStatusCode(404);
        }

        $user->delete();

        return Response::json([ 'message' => 'Deleted' ])->setStatusCode(200);
    }
}
