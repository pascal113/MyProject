<div class="block-wash-packs cards cards--three cards--gray">
    @foreach ($products as $product)
        @if (!$product)
            @continue
        @endif

        @include('components.blocks.product', ['product' => $product])
    @endforeach
</div>
