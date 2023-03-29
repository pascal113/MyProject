<section class="block-contact">
    <div class="block-contact__content">
        @if(!empty($icon))
            <i class="icon icon--{{ $icon }}"></i>
        @endif
        <h2 class='section-title section-title--margin-top'>{{ $heading }}</h2>
        @include('components.base.paragraphs', ['paragraphs' => $paragraphs])
        @if ($button ?? null)
            @include('components.base.button', (array) $button)
        @endif
    </div>
</section>
