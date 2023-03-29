@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @include('components.blocks.order-summary', [ 'hideSummary' => true ])
        <form id="payment-form" action="{{ route('checkout.payment-methods.store') }}" method="post">
            @csrf

            <div class="wrapper">
                <section class="section">
                    <div class="
                        section-row
                        section-row--padded-top
                        section-row--center
                        section-row--sm
                    ">
                        @include('components.blocks.notifications')

                        <h3 class="section-title">Payment Method</h3>
                        <hr />
                        <p><i class="checkout-icon checkout-icon--payment"></i></p>
                        <p class="checkout-question">Which card would you like us to bill charges to?</p>
                        <div class="text-center centered-radio-group">
                            <ul class="clean-list">
                                @if ($paymentMethod)
                                    <li>
                                        <label for="payment_method-existing" class="radio-group__radio weight-default">
                                            <input type="radio" id="payment_method-existing" name="payment_method" value="existing" class="@error('payment_method') has-error @enderror" data-test-id="payment:existing">To the {{ $paymentMethod->brand }} card on file ending in {{ $paymentMethod->last4 }}
                                        </label>
                                    </li>
                                @endif
                                <li class="weight-default">
                                    <label for="payment_method-new" class="radio-group__radio weight-default">
                                        <input type="radio" id="payment_method-new" name="payment_method" value="new" class="@error('payment_method') has-error @enderror" data-test-id="payment:new">To a new payment method
                                    </label>
                                </li>
                            </ul>
                            <input type="hidden" id="verification-complete" value="" />
                            <input type="hidden" id="change-payment-method-accepted" value="" />

                            @error('payment_method')
                                <span class="error-text invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @error('email_verification')
                                <span class="error-text invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </section>
                @include('components.blocks.footer-return', [
                    'returnLink' => [
                        'theme' => 'checkout',
                    ],
                    'button' => [
                        'text' => 'Continue',
                        'type' => 'button',
                        'tags' => 'id="continue_button" onclick="event.preventDefault(); this.disabled = true; onClickContinue(); return false;"',
                        'testId' => 'payment:continue'
                    ],
                ])
            </div>
        </form>
    </main>
    @if ($user && !$user->email_verified_at)
        @include('components.popups.email-verification', [
            'onSuccess' => "$('#verification-complete').val(1);",
            'messageType' => 'process-payment',
        ])
    @endif
    @if ($paymentMethod && $hasNonGiftWashClubProducts)
        @include('components.popups.change-payment-method-warning', [
            'onConfirm' => "$('#change-payment-method-accepted').val(1); $('#payment-form').submit();",
        ])
    @endif
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            @if ($user && !$user->email_verified_at)
                $('input[name="payment_method"]').click(function() {
                    if ($('#verification-complete').val()) {
                        return;
                    }

                    $.featherlight($('#email-verification'));
                });
            @endif
        });

        function onClickContinue() {
            @if ($paymentMethod && $hasNonGiftWashClubProducts)
                if ($('#payment_method-new').prop('checked') && !$('#change-payment-method-accepted').val()) {
                    $('#continue_button').prop('disabled', false);                    
                    $.featherlight($('#change-payment-method-warning'));
                }
                else {
                    $('#payment-form').submit();
                }
            @else
                $('#payment-form').submit();
            @endif
        }
    </script>
@endpush
