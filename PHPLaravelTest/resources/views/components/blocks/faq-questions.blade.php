<div class="block-expandable-section">
    @foreach($questions as $question)
    <!-- Question -->
    <div>
        <h2 id="{{ \Illuminate\Support\Str::slug($question->heading) }}" class="block-expandable-section__section section-title section-trigger">{{ $question->heading }}</h2>
        <div class="section-content">
            @include('components.base.wysiwyg', ['content' => $question->wysiwyg ?? null])
        </div>
    </div>
    <!-- /Question -->
    @endforeach
</div>
@push('js')
    <script>
        $(document).ready(function () {
            // Hide/Show blocks
            $('.section-trigger').click(function (e) {
                e.preventDefault();
                $(this).toggleClass('is-active');
                $(this).next('div.section-content').toggleClass('is-active');
                if ($(this).hasClass('is-active')) {
                    window.location = "#" +  $(this).attr('id');
                }
            });
            const url = window.location.href;
            var anchorIndex = url.indexOf("#");
            if (anchorIndex !== -1) {
                const anchor = url.substring(anchorIndex+1);
                var el =  $('#' + anchor);
                el.toggleClass('is-active');
                el.next().toggleClass('is-active');
            }
        });
    </script>
@endpush
