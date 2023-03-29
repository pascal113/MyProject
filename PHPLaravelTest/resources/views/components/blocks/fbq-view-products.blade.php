@push('fbq')
    <script>
        fbq('track', 'ViewContent', {
            content_category: '{{ $category }}',
            content_type: 'product_group',
            content_ids: @json(collect($products)->map(function($product) {
                return $product->id ?? null;
            })),
            currency: 'USD'
        })
    </script>
@endpush
