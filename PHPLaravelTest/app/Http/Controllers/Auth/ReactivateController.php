<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GatewayService;
use App\Services\OnScreenNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ReactivateController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request, string $email): View
    {
        $meta = parent::meta();

        $mode = $request->get('mode', null);

        $with = [
            'meta' => $meta,
            'email' => $email,
            'mode' => $mode,
        ];

        if ($request->has('success')) {
            $with = OnScreenNotificationService::with([
                'message' => trans('passwords.sent'),
                'level' => 'success',
            ], $with);

            return view('auth.reactivate.success')
                ->with($with);
        } elseif ($request->has('errors')) {
            $with = OnScreenNotificationService::with([
                'message' => array_values($request->get('errors') ?? [])[0] ?? 'An unknown error has occurred.',
                'level' => 'error',
            ], $with);
        }

        return view('auth.reactivate.index')
            ->withInput($request->get('inputs') ?? null)
            ->with($with);
    }

    /**
     * Show form with email input and hidden token
     *
     * @return RedirectResponse|View
     */
    public function showResetForm(Request $request, string $token, ?string $legacy = null)
    {
        if ($errors = ($request->get('errors') ?? null)) {
            $inputs = $request->get('inputs') ?? [];

            return redirect()->route('reactivate.reset', [ 'token' => $inputs['token'] ])
                ->withErrors($errors)
                ->withInput($inputs);
        }

        $mode = (int)$legacy === 1 ? 'legacy' : 'reactivate';

        $meta = parent::meta();

        return view('auth.reactivate.reset', compact('meta', 'token', 'mode'));
    }

    /**
     * Show success screen
     */
    public function showSuccess(Request $request): RedirectResponse
    {
        Session::flash('redirectTo', route('my-account.index', [ 'expandedSection' => 'reactivated' ]));

        return GatewayService::ssoRedirect();
    }
}
