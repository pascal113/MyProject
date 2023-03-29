@if (($view ?? null) === 'browse')
    @php
        $products = $data->{$row->details->table} ?? [];
        if ($hasMore = $products->count() > 2) {
            $products = $products->take(2);
        }
    @endphp
    @forelse ($products as $product)
        <li>{{ $product->pivot->qty }}x {{ $product->name }}</li>
    @empty
        <p>No products.</p>
    @endforelse
    @if ($hasMore)
        <p>...and more</p>
    @endif
@else
    @forelse ($dataTypeContent->{$row->details->table} ?? [] as $product)
        <li>
            {{ $product->pivot->qty }}x
            <a href="{{ route('products.show', [$product->id]) }}" target="_product">{{ \App\Models\Product::getNameWithTerm($product, $product->pivot->variant) }}</a>
            =
            @if ($product->pivot->discount > 0)
                <strike>${{ number_format($product->pivot->pre_discount_sub_total / 100, 2) }}</strike>
            @endif
            ${{ number_format($product->pivot->sub_total / 100, 2) }}
        </li>
    @empty
        <p>No products.</p>
    @endforelse
@endif
