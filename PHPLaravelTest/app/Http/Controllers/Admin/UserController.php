<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends \TCG\Voyager\Http\Controllers\VoyagerUserController
{
    /**
     * BR(E)AD: "Edit"->update a single User
     */
    public function update(Request $request, $id)
    {
        $request->merge([
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        parent::update($request, $id);

        return (new AdminController())->redirectToUpdatedSuccess($request, $id);
    }

    /**
     * BRE(A)D: "Add"->store a single Page
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        if (!$request->get('role_id')) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'message' => 'Please select a role.',
                    'alert-type' => 'error',
                ]);
        }
        if (!$request->get('password')) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'message' => 'Please specify a password.',
                    'alert-type' => 'error',
                ]);
        }

        /**
         * We need to create a User object and set role relation to test authorization
         * since $model in UserPolicy will not have role present
         */
        $user = new User($request->all());
        $role = Role::find($request->get('role_id'));
        $user->role()->associate($role);

        // Check permission
        $this->authorize('edit', $user);

        $request->merge(['_tagging' => true]); // Tell Voyager to give us a JsonResponse
        $data = parent::store($request);

        return (new AdminController())->redirectToCreatedSuccess($request, $data->getData()->data->id);
    }
}
