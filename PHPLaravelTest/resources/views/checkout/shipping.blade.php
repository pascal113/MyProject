@extends('layouts.web')

@if (session()->get('isNewlyRegistered'))
    @include('components.blocks.fbq-complete-registration')
@endif

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--short',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        @include('components.blocks.order-summary', [ 'hideSummary' => false ])

        <form id="shipping-form" action="{{ route('checkout.shipping') }}" method="post">
            @csrf
            <div class="wrapper">
                <section class="section">
                    <div class="
                        section-row
                        section-row--padded
                        section-row--center
                        section-row--sm
                    ">
                        @include('components.blocks.notifications')

                        <h3 class="section-title">Shipping Information</h3>
                        <hr />
                        <div class="
                            section-row
                            section-row--xsm
                            section-row--left
                        ">
                            @php
                                if (Auth::user()) {
                                    $user = Auth::user();
                                } else {
                                    $user = new \App\Models\User();
                                    $user->first_name = Session::get('user.first_name');
                                    $user->last_name = Session::get('user.last_name');
                                    $user->shipping_address = Session::get('user.shipping.address');
                                    $user->shipping_city = Session::get('user.shipping.city');
                                    $user->shipping_state = Session::get('user.shipping.state');
                                    $user->shipping_zip = Session::get('user.shipping.zip');
                                    $user->shipping_phone = Session::get('user.shipping.phone');
                                }
                            @endphp
                            @include('components.forms.shipping-address', [ 'user' => $user ])
                        </div>
                    </div>
                </section>
                @include('components.blocks.footer-return', [
                    'returnLink' => [
                        'theme' => 'checkout',
                    ],
                    'button' => [
                        'text' => 'Continue',
                        'type' => 'submit',
                        'tags' => 'onclick="event.preventDefault(); $(\'#shipping-form\').submit(); this.disabled = true; return false;"',
                    ],
                ])
            </div>
        </form>
    </main>
@endsection
