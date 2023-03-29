<section class="block-page-intro">
    <div class="block-page-intro__content">
        <h1 class="page-title make-trademarks-superscript">{{ $heading }}</h1>
        @if ($paragraphs)
            @foreach ($paragraphs as $paragraph)
                <p class="page-intro make-trademarks-superscript">{{ $paragraph }}</p>
            @endforeach
        @endif
    </div>
</section>
