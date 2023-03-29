<section class="block-press-center">
    <div class="block-press-center__content">
        @if(!empty($pressCenter->icon))
        <i class="icon icon--{{ $pressCenter->icon }}"></i>
        @endif
        <h2 class='section-title section-title--margin-top'>{{ $pressCenter->heading }}</h2>
        @include('components.base.paragraphs', ['paragraphs' => $pressCenter->paragraphs])
        @if ($pressCenter->button ?? null)
            @include('components.base.button', array_merge((array) $pressCenter->button, ['wrapperClass' => 'button-row--no-margin']))
        @endif
    </div>
</section>
