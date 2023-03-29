@push('fbq')
    @if (Auth::user())
        <script>
            fbq('track', 'CompleteRegistration', {
                content_name: '{{ Auth::user()->full_name }}',
                currency: 'USD'
            });
        </script>
    @endif
@endpush
