<?php

namespace App\Http\Controllers;

use App\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\GatewayService;
use App\Services\OnScreenNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MembershipController extends Controller
{
    /**
     * @var array
     */
    public const CANCELLATION_REASONS = [
        'Funds',
        'Weather',
        'Moving',
        'Sold Vehicle',
        'Unsatisfied',
        'Other',
    ];

    /**
     * Shows Membership by id
     */
    public function show(string $id)
    {
        $membership = Membership::getForCurrentUserById($id);

        if (!$membership) {
            self::abort(404, 'Not Found. Refreshing or trying again after a little while may resolve this issue.');
        } elseif (!$membership->is_mine && !$membership->is_gift) {
            self::abort(403, [ 'resourceType' => 'membership' ]);
        } elseif (!$membership->id_from_purchase_or_wash_connect && !Auth::user()->email_verified_at) {
            return redirect()->to(route('email.verify', [
                'redirectTo' => '/'.request()->path(),
                'messageType' => 'restricted-information',
            ]));
        }

        $user = Auth::user();
        $paymentMethod = $user->wash_connect->payment_method ?? null;

        $buttons = collect([
            'update-payment-method' => $paymentMethod && $membership->wash_connect->membership && ($membership->club->is_recurring ?? null) && !$membership->is_pending_termination && !$membership->is_fully_terminated,
            'modify' => $membership->wash_connect->membership && $membership->variantsAvailableForModifyingTo()->count() && !$membership->is_pending_termination && !$membership->is_fully_terminated && (!$membership->pending_modification || $membership->pending_modification->is_cancelable),
            'terminate' => $membership->wash_connect->membership && $membership->is_terminatable,
            'cancel-termination' => $membership->wash_connect->membership && $membership->is_pending_termination,
            'reactivate' => $membership->is_reactivatable,
        ])->filter(function ($bool) {
            return $bool;
        });

        return parent::view('my-account.memberships.show', compact('buttons', 'membership'));
    }

    /**
     * Terminate a Membership
     */
    public function storeTerminate(Request $request, string $washConnectId): RedirectResponse
    {
        $validator = self::validateRequest($request, [ 'cancellation_reasons' => 'required|array|min:1' ]);

        if ($validator->fails()) {
            return self::handleFailedValidation($validator, $request);
        }

        $membership = Membership::getForCurrentUserById($washConnectId);

        if (!$membership) {
            self::abort(404);
        }

        $response = $membership->terminate([
            'reasons' => $request->get('cancellation_reasons'),
            'comments' => $request->get('comments'),
        ]);

        if (!$response->success) {
            return redirect()
                ->route('my-account.memberships.show', [ 'id' => $membership->id_from_purchase_or_wash_connect ])
                ->with(OnScreenNotificationService::with([
                    'message' => $response->message,
                    'level' => 'error',
                ]));
        }

        $to_email = config('mail.unlimited_wash_club.address');
        $to_name = 'Brown Bear Car Wash';

        $content = "A customer has terminated their membership.\n\n<b>RFID:</b> ".($membership->wash_connect->vehicle->rfid ?? 'Unknown')."\n\n<b>Reasons:</b>\n";
        foreach (static::CANCELLATION_REASONS as $reason) {
            $content .= ($request->input('cancellation_reasons.'.$reason) === 'on' ? '☑' : '☐').' '.$reason."\n";
        }
        $content .= "\n<b>Comments:</b>\n".($request->get('comments') ?? '<i>(none)</i>');
        $content = [
            'html' => str_replace("\n", '<br>', $content),
            'text' => $content,
        ];

        Mail::send(
            [
                'html' => 'emails.general-html',
                'text' => 'emails.general-text',
            ],
            [ 'content' => $content ],
            function ($m) use ($to_email, $to_name) {
                $m->from(config('mail.from.address'), config('mail.from.name'));
                $m->to($to_email, $to_name)->subject('Customer has terminated their membership');
            }
        );

        return redirect()
            ->route('my-account.memberships.show', [ 'id' => $membership->id_from_purchase_or_wash_connect ])
            ->with(OnScreenNotificationService::with([
                'message' => 'This membership has been terminated.',
                'level' => 'success',
            ]));
    }

    /**
     * Cancel a pending Modification for a Membership
     */
    public function storeCancelModification(string $washConnectId): RedirectResponse
    {
        $membership = Membership::getForCurrentUserById($washConnectId);

        if (!$membership || !$membership->pending_modification) {
            self::abort(404);
        }

        $response = $membership->pending_modification->delete();

        if (!$response->success) {
            return redirect()
                ->route('my-account.memberships.show', [ 'id' => $membership->id_from_purchase_or_wash_connect ])
                ->with(OnScreenNotificationService::with([
                    'message' => 'An error occurred and we were unable to cancel your pending conversion.',
                    'level' => 'error',
                ]));
        }

        return redirect()
            ->route('my-account.memberships.show', [ 'id' => $membership->id_from_purchase_or_wash_connect ])
            ->with(OnScreenNotificationService::with([
                'message' => 'Your conversion has been canceled.',
                'level' => 'success',
            ]));
    }

    /**
     * Cancel a pending Termination
     */
    public function storeCancelTermination(string $washConnectId): RedirectResponse
    {
        $membership = Membership::getForCurrentUserById($washConnectId);

        if (!$membership) {
            self::abort(404);
        }

        $response = $membership->cancelTermination();

        if (!$response->success) {
            return redirect()
                ->route('my-account.memberships.show', [ 'id' => $membership->id_from_purchase_or_wash_connect ])
                ->with(OnScreenNotificationService::with([
                    'message' => 'An error occurred and we were unable to cancel your pending membership termination.',
                    'level' => 'error',
                ]));
        }

        return redirect()
            ->route('my-account.memberships.show', [ 'id' => $membership->id_from_purchase_or_wash_connect ])
            ->with(OnScreenNotificationService::with([
                'message' => 'Your membership termination has been canceled.',
                'level' => 'success',
            ]));
    }

    /**
     * Reactivate
     */
    public function storeReactivate(string $washConnectId): RedirectResponse
    {
        $membership = Membership::getForCurrentUserById($washConnectId);

        if (!$membership) {
            self::abort(404);
        }

        $variant = ProductVariant::where('wash_connect_program_id', $membership->wash_connect->membership->program_id)->first();

        if (!$variant) {
            return redirect()
                ->back()
                ->with(OnScreenNotificationService::with([
                    'message' => 'Unable to determine club for reactivation.',
                    'level' => 'error',
                ]));
        }

        $options = [
            'variant_id' => $variant->id,
            'variant_name' => $variant->name,
            'reactivates_membership_wash_connect_id' => $membership->wash_connect->membership->id,
        ];

        $qty = 1;

        Cart::add([
            'id' => $variant->product->id,
            'name' => $variant->product->name,
            'qty' => $qty,
            'price' => $variant->product->getPriceEach($variant, $qty),
            'weight' => 0,
            'options' => $options,
        ])
            ->associate(Product::class);

        Cart::updateAndSave();

        return redirect()->route('cart.index');
    }
}
