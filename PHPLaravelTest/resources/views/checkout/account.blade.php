@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @include('components.blocks.order-summary')

        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                    section-row--sm
                ">
                    @include('components.blocks.notifications')

                    @if (Cart::hasDigitalProducts())
                        <p class="page-intro page-intro--large">A Brown Bear Digital Account is required to purchase Wash Clubs and digital products. </p>
                    @endif

                    <h3 class="section-title">Have a Brown Bear Account?</h3>
                    <hr />

                    @if (Cart::hasDigitalProducts())
                        <p class="page-intro">Please sign in to to complete this purchase.</p>
                    @else
                        <p class="page-intro">Sign in to your Brown Bear Digital Account to speed up checkout and have access to order history and status.</p>
                    @endif
                </div>
            </section>
        </div>

        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--center
                    section-row--sm
                ">
                    <div class="
                        section-row
                        section-row--xsm
                        section-row--left
                    ">
                        <form id="login" action="{{ config('services.gateway.base_url').config('services.gateway.oauth_login_url') }} " method="post">
                            @csrf
                            <input type="hidden" name="redirectAfterLogin" value="{{ GatewayService::redirectRoute('sso.after-login', [ 'restoreSavedCart' => 0 ]) }}" />
                            @include('components.forms.login', [ 'redirectTo' => request()->path() ])

                            <div class="
                                button-row
                                button-row--section-margin-half
                                button-row--center
                            ">
                                <button type="submit" class="button" data-test-id="login:submit"><span>Sign in</span></button>
                                <a class="forgot-password" href="{{ route('password.request') }}">Forgot Password</a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <form id="register" action="{{ route('checkout.store-account') }}" method="post">
            @csrf

            <div class="wrapper">
                <section class="section">
                    <div class="
                        section-row
                        section-row--padded
                        section-row--center
                        section-row--sm
                    ">
                        <h3 class="section-title">New to Brown Bear?</h3>
                        <hr />
                        <div class="
                            section-row
                            section-row--xsm
                            section-row--left
                        ">
                            @if (Cart::hasDigitalProducts())
                                <p class="page-intro">Please enter your contact details to get started.</p>
                            @else
                                <p class="page-intro">Enter your contact details to get started.</p>
                            @endif

                            <input name="redirectAfterRegister" type="hidden" value="{{ GatewayService::redirectRoute('sso.after-checkout-register') }}" />
                            <input name="origin" type="hidden" value="brownbear.com/checkout/account" />
                            <input name="shortOrigin" type="hidden" value="checkout/account" />

                            @include('components.forms.register', [
                                'requirePassword' => Cart::hasDigitalProducts(),
                                'firstName' => Session::get('user.first_name'),
                                'lastName' => Session::get('user.last_name'),
                                'email' => Session::get('user.email'),
                            ])
                        </div>
                    </div>
                </section>
            </div>
            @include('components.blocks.footer-return', [
                'returnLink' => [
                    'theme' => 'checkout',
                ],
                'button' => [
                    'text' => Cart::hasPhysicalProducts() ? 'Continue to Shipping' : 'Create Account & Continue',
                    'type' => 'submit',
                ],
            ])
        </form>
    </main>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            var anchor = window.location.hash;
            if (anchor === '#register') {
                $('#login input').removeClass('has-error');
                $('#login .error-text').remove();
            }
        });
    </script>
@endpush
