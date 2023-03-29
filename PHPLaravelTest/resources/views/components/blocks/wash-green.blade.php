<div class="wrapper wrapper--green">
    <section class="section">
        <div class="
            section-row
            section-row--padded
            align-left-desktop
            block-wash-green
            make-trademarks-superscript
        ">
            <h2 class='section-title text-center section-spacing-below'>{{ $heading }}</h2>
            {{ $media ?? null }}

            <div class="row-img-left">
                <div>
                    <img class="tree-bear" src="{{ asset('images/img-bearWithTree.svg') }}" alt="Eco Tree Bear">
                    <img class="tree-bear" src="{{ asset('images/img-washGreen-blue.svg') }}" alt="Wash green">
                </div>
                <div class="block-wash-green__content">
                    @include('components.base.wysiwyg', ['content' => $paragraphs])
                </div>
            </div>
        </div>

        @if ($button ?? null)
            <div class="
                section-row
                section-row--padded-bottom
                section-row--center
            ">
                @include('components.base.button', (array) $button)
            </div>
        @endif
    </section>
</div>
