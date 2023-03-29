@php
    $max = $max ?? 6;
    $showMore = $showMore ?? true;
@endphp

<section class="block-featured-news">
    <h2 class='section-title'>{{ $heading }}</h2>
    <div class="block-featured-news__content">
        @foreach ($items as $index => $item)
            <ul class="featured-news-list {{$index >= $max ? 'hidden' : ''}}">
                @if ($item->date ?? null)
                    <li class="featured-news-list__date">{{ \Carbon\Carbon::parse($item->date)->format(config('format.date')) }}</li>
                @endif
                @if ($item->publication ?? null)
                    <li class="featured-news-list__publication">{{ $item->publication }}</li>
                @endif
                <li class="featured-news-list__link"><a {{ !empty($item->url->openInNewTab) ? 'target="_blank"' : '' }} href="{{ $item->url->value }}">{{ $item->title }}</a></li>
            </ul>
        @endforeach
    </div>
    @if ($showMore && sizeof($items) > $max)
        <div class="button-row button-row--no-margin">
            <button class="button show-more-action">Show More</button>
        </div>
    @endif
</section>
@push('js')
    <script>
        $(document).ready(function () {
            $('.show-more-action').click(function () {
                var dealerImages = $('.block-featured-news__content > .hidden');
                if (dealerImages.length > 0) {
                    $(this).text('Show Less News');
                    dealerImages.removeClass('hidden');
                    dealerImages.addClass('visible');
                } else {
                    dealerImages = $('.block-featured-news__content > .visible');
                    $(this).text('Show More News');
                    dealerImages.addClass('hidden');
                    dealerImages.removeClass('visible');
                }
            })
        });
    </script>
@endpush

