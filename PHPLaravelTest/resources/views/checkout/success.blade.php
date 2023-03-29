@extends('layouts.web')

@if (session()->get('isNewlyRegistered'))
    @include('components.blocks.fbq-complete-registration')
@endif
@push('fbq')
    @php
        $contents = preg_replace('/, $/', '', $order->products->reduce(function ($acc, $product) {
            $acc .= '{id: '.$product->id.', quantity: '.$product->pivot->qty.'}, ';

            return $acc;
        }, ''));
    @endphp

    <script>
        fbq('track', 'AddPaymentInfo', {
            contents: [{{ $contents }}],
            content_type: 'product',
            value: {{ $order->total }},
            currency: 'USD'
        });
        fbq('track', 'Purchase', {
            contents: [{{ $contents }}],
            content_ids: @json($order->products->map(function ($product) {
                return $product->id;
            })),
            content_type: 'product',
            num_items: {{ $order->products->reduce(function ($numItems, $product) {
                return $numItems + $product->pivot->qty;
            }, 0) }},
            value: {{ $order->total }},
            currency: 'USD'
        });
    </script>
@endpush

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper wrapper--gray">
            <section class="section">
                <div class="
                    section-row
                    section-row--top
                    section-row--center
                ">
                    @if (Auth::user())
                        <h2 class='page-title page-title--no-margin'>Hi {{ Auth::user()->first_name }}</h2>
                    @else
                        <h2 class='page-title page-title--no-margin'>Awesome!</h2>
                    @endif
                </div>
            </section>
        </div>
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    @include('components.blocks.notifications')

                    <h3 class="section-title">Thank You for Your Order</h3>
                    <hr />
                    <div class="section-row">
                        @if (!$order->user)
                            <form action="{{ config('services.gateway.base_url').config('services.gateway.oauth_register_url') }}" method="post">
                                @csrf

                                <input type="hidden" name="redirectAfterRegister" value="{{ GatewayService::redirectRoute('sso.after-order-success-register', [ $order->id, $accessToken ]) }}" />
                                <input type="hidden" name="first_name" value="{{ $order->customer_first_name }}" />
                                <input type="hidden" name="last_name" value="{{ $order->customer_last_name }}" />
                                <input type="hidden" name="email" value="{{ $order->customer_email }}" />
                                <input type="hidden" name="shipping_first_name" value="{{ $order->shipping_first_name }}" />
                                <input type="hidden" name="shipping_last_name" value="{{ $order->shipping_last_name }}" />
                                <input type="hidden" name="address" value="{{ $order->shipping_address }}" />
                                <input type="hidden" name="city" value="{{ $order->shipping_city }}" />
                                <input type="hidden" name="state" value="{{ $order->shipping_state }}" />
                                <input type="hidden" name="zip" value="{{ $order->shipping_zip }}" />
                                <input type="hidden" name="phone" value="{{ $order->shipping_phone }}" />
                                <input name="origin" type="hidden" value="brownbear.com/checkout/success/{{ $order->id }}" />
                                <input name="shortOrigin" type="hidden" value="checkout/success" />

                                <div class="link-black">
                                    <p class="page-intro">To save your order information and receive status updates, please enter a password to <strong>Sign In</strong> to your existing <strong>Brown Bear Digital Account</strong> or <strong>Create a New Account</strong>. </p>
                                </div>
                                <div class="
                                    section-row
                                    section-row--xsm
                                    section-row--left
                                ">
                                    <div class="field-wrapper">
                                        <label for="password">Password</label>
                                        <span class="form-instructions">Numbers, letters and a special character please</span>
                                        @include('components.blocks.password-input', ['ignorePost' => false])
                                    </div>
                                    <div class="form-row__info">
                                        <p>By clicking Create My Account you accept the Brown Bear Digital <a href="{{ cms_route('support.policies') }}">Privacy Policy and Terms and Conditions</a>.</p>
                                    </div>
                                </div>
                                <div class='button-row text-center'>
                                    <button class='button'>Submit</button>
                                    <a class="forgot-password" href="{{ route('password.request') }}">Forgot Password</a>
                                </div>
                            </form>

                            <div class="
                                section-row
                                section-row--sm
                                section-row--center
                                section-row--padded
                            ">
                                <div>
                                    <img class="img-bb-digital img-content img-content--center" src="{{ asset('images/img-bb-digital.svg') }}" alt="Brown Bear Digital">
                                </div>
                                <p class="page-intro link-black">
                                    <strong>More About Brown Bear Digital</strong><br />
                                    Brown Bear Digital gives you access to Order History, Notification Preferences and more, all in one place.
                                </p>
                            </div>
                        @else
                            <div>
                                <a href="{{ route('my-account.index') }}">
                                    <img class="img-bb-digital img-content img-content--center" src="{{ asset('images/img-bb-digital.svg') }}" alt="Brown Bear Digital">
                                </a>
                            </div>
                            <div class="link-black">
                                <p class="page-intro">Thank you for your order. We’ll get to work on your order right away. If you have opted-in to emails, you will receive an order receipt, and, if your order requires shipping, you will receive a shipping notice via email. Your order details and status are also available in <a href="{{ route('my-account.index') }}"><strong>Brown Bear Digital</strong></a>.</p>
                            </div>
                            <hr>
                            <div class="button-row">
                                <a href="{{ cms_route('/shop') }}" class="button">Continue Shopping</a>
                                <a href="{{ route('my-account.index') }}" class="button button--light-blue">Visit Brown Bear Digital</a>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
