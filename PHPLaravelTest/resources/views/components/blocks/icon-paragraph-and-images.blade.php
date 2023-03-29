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

        @if (!empty($paragraphs))
            <div class="section-intro">
                @include('components.base.wysiwyg', [ 'content' => $paragraphs ])
            </div>
            <hr>
        @endif

        @include('components.blocks.images-linked', [ 'items' => $images->items ?? [] ])
    </div>
</section>
