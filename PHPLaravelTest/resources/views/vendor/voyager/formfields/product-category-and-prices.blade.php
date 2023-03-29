@php
    $old = (object)[
        'productCategoryId' => old('product_category_id') ?? $dataTypeContent->product_category_id ?? null,
        'price' => old('price') ?? $dataTypeContent->price ?? null,
        'prices' => old('prices') ?? $dataTypeContent->prices ?? null,
        'numWashes' => old('num_washes') ?? $dataTypeContent->num_washes ?? null,
        'priceMonthly' => old('price_monthly') ?? $dataTypeContent->variants->firstWhere('name', 'Monthly')->price ?? null,
        'priceYearly' => old('price_yearly') ?? $dataTypeContent->variants->firstWhere('name', 'Yearly')->price ?? null,
    ];
@endphp

<div id="product-category-and-prices-formfield">
    <product-category-and-prices-formfield inline-template>
        <voyager-formfields-product-category-and-prices
            :csrf-token="'{{ csrf_token() }}'"
            name="{{ $row->name }}"
            :options='@json($row->options)'
            :row='@json($row)'
            :categories='@json($allCategories->toArray())'
            :labels='@json($labels)'
            :old='@json($old)'
        />
    </product-category-and-prices-formfield>
</div>

@push('javascript')
    <script>
        Vue.component('product-category-and-prices-formfield', {
            props: {
            },
        });

        var productCategoryAndPricesVm = new Vue({ el: '#product-category-and-prices-formfield' });
    </script>
@endpush
