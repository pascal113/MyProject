<div class="show-hide" id="show-hide{{ ($id ?? null) ? '-'.$id : '' }}">
    {{ $slot }}
</div>

@push('js')
    <script>
        $(document).ready(function() {
            // Hide/Show blocks
            $('.show-hide__trigger{{ ($id ?? null) ? '-'.$id : '' }}').click(function(e) {
                e.preventDefault();

                $('#show-hide{{ ($id ?? null) ? '-'.$id : '' }}').toggleClass('is-active');

                $(this).text(function(i, text) {
                    return text === '{{ $showText ?? 'View Details' }}' ? '{{ $hideText ?? 'Hide Details' }}' : '{{ $showText ?? 'View Details' }}';
                })
            });
        });
    </script>
@endpush
