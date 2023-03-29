@extends('flexible-page-cms.templates.overview')

@push('fbq')
    <script>
        fbq('track', 'ViewContent', {
            content_category: 'Shopping',
            content_type: 'product_group',
            currency: 'USD'
        })
    </script>
@endpush
