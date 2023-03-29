@php
    $user = Auth::user();
    $messageType = $messageType ?? request()->get('messageType') ?? null;
    $message = ($messageType === 'reminder' ? 'Thank you for verifiying your email address.' : null)
        ?? ($messageType === 'account-information' ? 'Before we can change your account information we need to verify your email address.' : null)
        ?? ($messageType === 'restricted-information' ? 'Before we can show you this information we need to verify your email address.' : null)
        ?? ($messageType === 'modify-membership' ? 'Before we can change your membership we need to verify your email address.' : null)
        ?? ($messageType === 'terminate-membership' ? 'Before we can terminate your membership we need to verify your email address.' : null)
        ?? ($messageType === 'cancel-membership-termination' ? 'Before we can cancel the termination of your membership we need to verify your email address.' : null)
        ?? ($messageType === 'complete-transaction' ? 'Before we can complete this transaction we need to verify your email address.' : null)
        ?? ($messageType === 'change-payment-method' ? 'Before we can change your payment method we need to verify your email address.' : null)
        ?? ($messageType === 'process-payment' ? 'Before we can process your payment we need to verify your email address.' : null)
        ?? 'Before we can proceed we need to verify your email address.';
@endphp

<h3 class="
    popup__title
    popup__title--large
    popup__title--black
    text-center
">
    Hi {{ $user->first_name ?? '' }}!
</h3>
<p class="text-center"><i class="checkout-icon checkout-icon--email"></i></p>
<p class="text-center">{{ $message }} Please check <strong>{{ $user->email ?? 'your inbox' }}</strong> and enter the 6-digit code we send you below.</p>
<div class="form-row">
    <div class="verification-code-digits">
        <input class="digit" data-index="1" type="number" min="0" max="9" step="1" inputmode="numeric" autocomplete="one-time-code" autofocus>
        <input class="digit" data-index="2" type="number" min="0" max="9" step="1" inputmode="numeric">
        <input class="digit" data-index="3" type="number" min="0" max="9" step="1" inputmode="numeric">
        <input class="digit" data-index="4" type="number" min="0" max="9" step="1" inputmode="numeric">
        <input class="digit" data-index="5" type="number" min="0" max="9" step="1" inputmode="numeric">
        <input class="digit" data-index="6" type="number" min="0" max="9" step="1" inputmode="numeric">
    </div>
    <div class="verification-code-loading" style="display: none;">
        <img src="{{ asset('/images/loading.gif') }}" alt="loading..." />
    </div>
    <div class="verification-code-notification"></div>
    <div class="
        button-row
        button-row--block
        button-row--section-margin-half
    ">
        <button class="button verification-code-submit">Submit Code & Continue</button>

        <div class="field-wrapper" style="display: flex; justify-content: center; margin-top: 40px;">
            <div class="re-send-email-recaptcha">{!! ReCaptcha::htmlFormSnippet() !!}</div>
        </div>
        <div class="re-send-email-loading" style="display: none;">
            <img src="{{ asset('/images/loading.gif') }}" alt="loading..." />
        </div>
        <div class="re-send-email-notification"></div>
        <button class="button verification-code-resend">Re-send Email</button>
    </div>
    <div class="
        button-row
        button-row--block
        button-row--section-margin-half
    ">
        <p class="text-center"><strong>Having trouble finding your verification code? Please contact us directly for assistance at 206.774.3737</strong></p>
    </div>
</div>

@push('js')
    <script>
        function showVerificationCodeError(message) {
            $('.verification-code-notification')
                .removeClass('info')
                .addClass('error')
                .text(message)
                .show();
        }
        function showVerificationCodeInfo(message) {
            $('.verification-code-notification')
                .removeClass('error')
                .addClass('info')
                .text(message)
                .show();
        }
        function verifyCode() {
            var code = '';
            for (var i = 0; i < 6; i++) {
                var input = $('.verification-code-digits input.digit[data-index="'+(i + 1).toString()+'"]');
                code += input.last().val().toString();
            }

            $('.verification-code-digits').hide();
            $('.verification-code-loading').show();
            $('.verification-code-notification').hide();

            window.axios.post('{{ config('services.gateway.base_url') }}/api/v2/email-verification/{{ $user->email ?? '' }}', { code: code })
                .then(function(resp) {
                    $('.verification-code-loading').hide();
                    $('.verification-code-submit').hide();
                    $('.verification-code-resend').hide();

                    {!! $onSuccess ?? '' !!}
                })
                .catch(function(error) {
                    $('.verification-code-loading').hide();
                    $('.verification-code-digits input.digit').val('');
                    $('.verification-code-digits').show();
                    $('.verification-code-digits input.digit[data-index="1"]').last().focus();
                    showVerificationCodeError('Please try again.');
                });
        }

        $(document).ready(function() {
            setTimeout(function() {
                $('.verification-code-digits input.digit[data-index="1"]').last().focus()
            }, 1000);

            $('.verification-code-digits input.digit').keyup(function(event) {
                // Handle backspace keypress
                if (event.keyCode === 8) {
                    if (event.target.value) {
                        event.target.value = '';
                    }
                    else {
                        var prevIndex = $(event.target).data().index - 1;
                        var prevInput = $('.verification-code-digits input.digit[data-index="'+prevIndex+'"]');
                        if (prevInput.length) {
                            prevInput.focus();
                            prevInput.value = '';
                        }
                    }

                    return;
                }

                if (event.target.value) {
                    var entered = event.target.value;

                    // If user entered more than one digit (e.g. by pasting), spread them out over the digit inputs
                    var nextIndex = $(this).data().index;
                    for (var i = nextIndex - 1; i < 6; i++) {
                        var digit = entered.slice(i - $(this).data().index + 1).charAt(0);

                        if (digit) {
                            $('.verification-code-digits input.digit[data-index="'+nextIndex.toString()+'"]').last().val(digit);

                            nextIndex++;
                        }
                    }

                    var nextInput = $('.verification-code-digits input.digit[data-index="'+nextIndex.toString()+'"]');
                    if (nextInput.length) {
                        nextInput.focus();
                    }
                    else {
                        window.setTimeout(function() {
                            verifyCode();
                        }, 200);
                    }
                }
            });

            $('.verification-code-submit').click(function() {
                verifyCode();
            });

            $('.verification-code-resend').click(function() {
                $('.re-send-email-notification').text('');
                $('.re-send-email-recaptcha').hide();
                $('.re-send-email-loading').show();

                window.axios.post('{{ config('services.gateway.base_url') }}/api/v2/email-verification/{{ $user->email ?? '' }}/resend', {
                    redirectTo: '{{ GatewayService::redirectUrl(request()->path()) }}',
                    recaptcha: $('[name=g-recaptcha-response]').val(),
                })
                    .then(function(resp) {
                        $('.re-send-email-loading').hide();
                        $('.verification-code-resend').hide();

                        $('.re-send-email-notification').removeClass('error').text('We have sent a new verification code. Please check your email.');

                        $('.verification-code-digits input.digit').first().focus();
                    })
                    .catch(function(error) {
                        $('.re-send-email-loading').hide();
                        $('.re-send-email-recaptcha').show();

                        var errorText = (error && error.response && error.response.data && error.response.data.errors && error.response.data.errors.recaptcha) || 'An error occurred. Please try again.';
                        $('.re-send-email-notification').addClass('error').text(errorText);
                    });
            });
        });
    </script>
@endpush
