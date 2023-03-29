<section class="section">
    <div class="
        section-row
        section-row--padded
        section-row--center
        section-row--sm
    ">
        @if (!empty($icon))
            <i class="icon icon--{{ $icon }}"></i>
        @endif

        <h2 class='section-title{{ !empty($icon) ? ' section-title--margin-top ' : '' }}'>{{ $heading }}</h2>

        @if (!empty($introParagraph))
            <div class="section-intro">
                @include('components.base.paragraphs', ['paragraphs' => $introParagraph])
            </div>
            <hr>
        @endif
        @include('components.base.wysiwyg', [ 'content' => $wysiwyg ])
        @include('components.base.button', array_merge((array)($button ?? []), ['wrapperClass' => 'button-row--no-margin']))
    </div>
</section>
