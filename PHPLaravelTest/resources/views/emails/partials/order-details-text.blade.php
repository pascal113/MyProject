ORDER NUMBER: {{ $order->id }} | ORDER DATE: {{ \Carbon\Carbon::parse($order->created_at)->format(config('format.date')) }}

@foreach ($order->products as $product)
    ITEM
    {{ $product->getNameWithTerm($product, $product->pivot->variant ?? null) }}
    @if ($product->pivot->modification)

    Club change occurs {{ ($product->pivot->modification->executes_at ?? null) ? \Carbon\Carbon::parse($product->pivot->modification->executes_at)->format(config('format.date')) : 'in the future' }}
    @endif
    @if ($product->is_wash_club && $product->pivot->membership_details_url)

    View Membership: {{ $product->pivot->membership_details_url }}
    @endif
    @if ($product->is_wash_club && ($product->pivot->membership_purchase->certificate_url ?? null))

    Print Certificate PDF: {{ $product->pivot->membership_purchase->certificate_url }}
    @endif

    QUANTITY
    {{ $product->pivot->qty }}

    SUBTOTAL
    ${{ number_format($product->pivot->sub_total / 100, 2) }}
    --------------------------------
@endforeach

@if ($order->discount > 0)
MERCHANDISE TOTAL
${{ number_format($order->sub_total + $order->discount, 2) }}
DISCOUNT TOTAL
-${{ number_format($order->discount, 2) }}
@endif
SUBTOTAL
${{ number_format($order->sub_total, 2) }}
Shipping ${{ number_format($order->shipping_price, 2) }}
Tax ${{ number_format($order->tax, 2) }}
--------------------------------

TOTAL
${{ number_format($order->total, 2) }}
