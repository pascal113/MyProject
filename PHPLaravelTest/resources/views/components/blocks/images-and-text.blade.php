<section class="section">
    <div class="
        section-row
        section-row--padded-top
        section-row--center
    ">
        @include('components.blocks.img-row', [
            'images' => (array)$images,
        ])
    </div>
    <div class="section-row section-row--padded section-row--sm section-row--center">
        @if (!empty($icon))
            @if (empty($iconType) || $iconType === 'icon')
                {{-- The Images & Text component previously ALWAYS had an icon, and the ability for user to choose between icon or image was added later. --}}
                {{-- https://bitbucket.org/flowerpress/brownbear.com/issues/541/add-support-for-image-inclusion-in-current --}}
                {{-- Therefore, pre-existing records have no `iconType` value stored, and are automatically icons. Hence the inclusion of `if (empty($iconType)` above. --}}
                <i class="icon icon--{{ $icon }}"></i>
            @elseif (!empty($iconType) && $iconType === 'image')
                <img class="img-content img-content--center" src="{{ asset($icon) }}" alt="">
            @endif
        @endif

        <h2 class='section-title text-center section-title--margin-top'>{{ $heading }}</h2>
        @include('components.base.wysiwyg', [
            'content' => $wysiwyg,
            'class' => 'wysiwyg-content--no-margin',
        ])
        @include('components.base.button', array_merge((array)($button ?? []), ['wrapperClass' => 'button-row--no-margin']))
    </div>
</section>
