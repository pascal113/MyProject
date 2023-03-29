<div class="popup splash-popup" id="splash">
    @if(isset($link_url))
        <a href="{{ $link_url }}">
            <img src="{{asset($image)}}" />
        </a>
    @else
        <img src="{{asset($image)}}" />
    @endif
</div>
@push('js')
    <script>
        $.featherlight($('#splash'), {
            beforeOpen: function() { $.featherlight.close() },
            variant: 'splash' // sets css class
        });
    </script>
@endpush
