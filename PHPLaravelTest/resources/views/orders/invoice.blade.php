@extends('layouts.print')

@section('content')
    <div class="invoice">
        <div class="row">
            <div class="col">
                <p>
                    3977 Leary Way NW<br>
                    Seattle WA 98107</br>
                    206.789.3700<br>
                    <a href="{{ cms_route('/') }}">www.brownbear.com</a>
                </p>
            </div>
            <div class="col">
                <p>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format(config('format.date')) }}<br>
                    <strong>Paid With:</strong> Payeezy<br>
                    @if ($order->transaction_id ?? null)
                        <strong>Transaction ID:</strong> {{ $order->transaction_id }}<br>
                    @endif
                    <strong>Order Number:</strong> {{ $order->id }}<br>
                </p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col">
                <p>
                    <strong>Bill To:</strong><br>
                    {{ $order->user->full_name ?? $order->customer_full_name }}<br>
                    @if ($order->customer_address)
                        {{ $order->customer_address }}<br>
                    @endif
                    @if ($order->customer_address_line_2)
                        {{ $order->customer_address_line_2 }}<br>
                    @endif
                    @if ($order->customer_phone)
                        {{ $order->customer_phone }}<br>
                    @endif
                    <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a>
                </p>
            </div>

            <div class="col">
                <p>
                    <strong>Ship To:</strong><br>
                    {{ $order->shipping_full_name }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_address_line_2 }}<br>
                </p>
            </div>
        </div>

        <table class="line-items">
            <tbody>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
                @foreach ($order->products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->getNameWithTerm($product, $product->pivot->variant) }}</td>
                        <td>{{ $product->pivot->qty }}</td>
                        <td>
                            @if ($product->pivot->discount > 0)
                                <strike>${{ number_format($product->pivot->pre_discount_sub_total / 100, 2) }}</strike>
                                <span style="font-size:14px; font-weight: normal; color:#666">(${{ number_format($product->pivot->sub_total / 100, 2) }})</span>
                            @else
                                ${{ number_format($product->pivot->sub_total / 100, 2) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col full-width right">
                <p>
                    @if ($order->discount > 0)
                        <strong>Merchandise Total:</strong> <strike>${{ number_format($order->sub_total + $order->discount, 2) }}</strike><br>
                        <strong>Discount:</strong> -${{ number_format($order->discount, 2) }}<br>
                    @endif
                    <strong>Subtotal:</strong> ${{ number_format($order->sub_total ?? 0, 2) }}<br>
                    <strong>Shipping:</strong> ${{ number_format($order->shipping_price ?? 0, 2) }}<br>
                    <strong>Tax:</strong> ${{ number_format($order->tax ?? 0, 2) }}<br>
                    <strong>Total:</strong> ${{ number_format($order->total ?? 0, 2) }}
                </p>
            </div>
        </div>
    </div>
@endsection
