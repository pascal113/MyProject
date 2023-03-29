<div class="popup popup-change-payment-method-warning" id="change-payment-method-warning">
    <h3 class="
        popup__title
        popup__title--large
        popup__title--black
        text-center
    ">
        Hi {{ Auth::user()->first_name }}!
    </h3>
    <p class="text-center"><i class="checkout-icon checkout-icon--payment"></i></p>
    <p class="text-center font-size--22"><strong>You are about to enter a new payment method, which will replace your {{ $paymentMethod->brand ?? 'current card' }}{{ ($paymentMethod->last4 ?? null) ? ' ending in '.$paymentMethod->last4 : '' }}.</strong></p>
    <p class="text-center">All recurring charges for monthly or yearly clubs associated with your Brown Bear Digital account will be billed to the new payment method you enter. Do you wish to continue?</p>
    <div class="
        button-row
        button-row--block
        button-row--extra-margin-top
    ">
        <button class="button"{!! ($onConfirm ?? null) ? ' onclick="'.$onConfirm.'"' : '' !!} data-test-id="payment:update">Yes, Update Card on File</button>
        <button class="button featherlight-close">Nevermind</button>
    </div>
</div>
