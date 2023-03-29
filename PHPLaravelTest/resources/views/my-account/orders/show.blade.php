@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' => 'Order Details',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title'>Order Date & Status</h2>
                    <hr>
                    <p>
                        <strong>Order Date</strong><br>
                        {{ \Carbon\Carbon::parse($order->created_at)->format(config('format.date')) }}
                    </p>
                    <p>
                        <strong>Order Number</strong><br>
                        {{ $order->id }}
                    </p>
                    @if ($order->hasPhysicalProducts())
                        <p>
                            <strong>Merchandise Status</strong><br>
                            {{ \App\Models\Order::STATUSES[$order->merch_status] ?? 'n/a' }}
                        </p>
                    @endif
                    @if ($order->hasWashClubProducts())
                        <p>
                            <strong>Club Status</strong><br>
                            {{ \App\Models\Order::STATUSES[$order->club_status] ?? 'n/a' }}
                        </p>
                    @endif
                </div>
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title'>Order Details</h2>
                    <hr class="section-below">
                </div>

                <div
                    id="summary-content"
                    class="wrapper wrapper--gray"
                    style="display: block;"
                >
                    <section class="section">
                        <div class="
                            section-row
                            section-row--padded
                        ">
                            @foreach($order->products as $product)
                                <div class="item-row">
                                    <div>
                                        <h3 class="section-subtitle">{{ $product->name }}</h3>

                                        @if ($product->pivot->modification)
                                            <p class="info-text">Club change occurs {{ ($product->pivot->modification->executes_at ?? null) ? \Carbon\Carbon::parse($product->pivot->modification->executes_at)->format(config('format.date')) : 'in the future' }}</p>
                                        @endif
                                        @if ($product->pivot->membership_details_url)
                                             <div class="item-row__links">
                                                <a href="{{ $product->pivot->membership_details_url }}">View Club Details</a>
                                                @if ($product->pivot->membership_purchase->certificate_url ?? null)
                                                    <a href="{{ $product->pivot->membership_purchase->certificate_url }}">Print Certificate PDF</a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="item-row__header">Quantity</span>
                                        <span class="item-row__content">{{ $product->pivot->qty }}</span>
                                    </div>
                                    @if ($product->pivot->variant ?? null)
                                        <div>
                                            <span class="item-row__header">Type</span>
                                            <span class="item-row__content">{{ $product->pivot->variant->name }}</span>
                                        </div>
                                    @endif
                                    <div class="item-row__price">
                                        <span class="item-row__header">Price</span>
                                        @if ($product->pivot->discount > 0)
                                            <span class="item-row__content"><span><strike>${{ number_format($product->pivot->pre_discount_sub_total / 100, 2) }}</strike></span></span>
                                        @endif
                                        <span class="item-row__content"><span>${{ number_format($product->pivot->sub_total / 100, 2) }}</span></span>
                                    </div>
                                </div>
                            @endforeach

                            <div class="item-subtotal">
                                @if ($order->discount > 0)
                                    <div class="item-subtotal__row item-subtotal__taxes">
                                        <span>Merchandise Total</span>
                                        <span><strike>${{ number_format($order->sub_total + $order->discount, 2) }}</strike></span>
                                    </div>
                                    <div class="item-subtotal__row item-subtotal__taxes">
                                        <span>Discount Total</span>
                                        <span>-${{ number_format($order->discount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="item-subtotal__row">
                                    <span class="item-subtotal__title">Subtotal</span>
                                    <span class="item-subtotal__cost">${{ number_format($order->sub_total, 2) }}</span>
                                </div>
                                <div class="item-subtotal__row item-subtotal__taxes">
                                    <span>Shipping</span>
                                    <span>${{ number_format($order->shipping_price, 2) }}</span>
                                </div>
                                <div class="item-subtotal__row item-subtotal__taxes">
                                    <span>Taxes</span>
                                    <span>${{ number_format($order->tax, 2) }}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="item-total">
                                <span class="item-total__title">Total</span>
                                <span class="item-total__cost">${{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="
                    section-row
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title section-title--section-margin-top'>Billing & Shipping Information</h2>
                    <hr class="section-below">
                    @if ($order->transaction_id ?? null)
                        <p>
                            <strong>Transaction ID</strong><br>
                            {{ $order->transaction_id }}
                        </p>
                    @endif
                    <p>
                        <strong>Shipping Address</strong><br>
                        @if ($order->hasPhysicalProducts())
                            {{ $order->shipping_full_name }}<br>
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                        @else
                            N/A
                        @endif
                    </p>
                    <p>
                        <strong>Phone Number</strong><br>
                        @if ($order->hasPhysicalProducts())
                            {{ $order->shipping_phone }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </section>
            @include('components.blocks.footer-return', [
                'returnLink' => [
                    'theme' => 'my-account',
                ],
            ])
        </div>
    </main>
@endsection
