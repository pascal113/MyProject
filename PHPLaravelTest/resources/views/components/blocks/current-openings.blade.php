<section class="block-current-openings">
    @if(!empty($icon))
        <i class="icon icon--{{ $icon }}"></i>
    @endif
    <h2 class='section-title'>{{ $heading }}</h2>
    @include('components.base.paragraphs', ['paragraphs' => $paragraphs])
    @if ($button ?? null)
        @include('components.base.button', array_merge((array) $button, ['wrapperClass' => 'button-row--no-margin']))
    @endif
</section>
