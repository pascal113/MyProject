<div class="block-getting-started">
    <section class="block-getting-started__content">
        <h2 class='section-title'>{{ $heading }}</h2>

        @include('components.base.paragraphs', ['paragraphs' => $paragraphs])

        @if ($button ?? null)
            @include('components.base.button', (array)$button)
        @endif
    </section>
</div>
