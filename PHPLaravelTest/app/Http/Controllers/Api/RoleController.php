<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    /**
     * Return array of all available Roles
     */
    public static function index(?string $myRole = null): JsonResponse
    {
        $roles = Role::all();

        if ($myRole) {
            if (!in_array($myRole, User::$rolesHierarchy)) {
                $roles = [];
            } else {
                $roles = collect($roles)->filter(function ($role) use ($myRole) {
                    return array_search($role->name, User::$rolesHierarchy) >= array_search($myRole, User::$rolesHierarchy);
                })->toArray();
            }
        }

        return Response::json([ 'data' => $roles ])->setStatusCode(200);
    }
}
