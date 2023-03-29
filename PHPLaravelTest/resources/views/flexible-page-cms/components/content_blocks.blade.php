@foreach ($blocks ?? [] as $block)
    @if ($block->componentType === 'quote')
        @include('components.blocks.pull-quote', [
            'quote' => $block->quote,
            'attribution' => $block->attribution,
            'image' => $block->image,
        ])
    @else
        <div class="wrapper {{ $block->wrapperColorClass ?? '' }}">
            @if ($block->componentType === 'accordion')
                @include('components.blocks.accordion', (array)$block)
            @elseif ($block->componentType === 'call_to_action')
                @include('components.blocks.call-to-action', (array)$block)
            @elseif ($block->componentType === 'cards')
                @include('components.blocks.cards', (array)$block)
            @elseif ($block->componentType === 'icon_and_paragraph')
                @include('components.blocks.icon-and-paragraph', (array)$block)
            @elseif ($block->componentType === 'icon_paragraph_and_images')
                @include('components.blocks.icon-paragraph-and-images', (array)$block)
            @elseif ($block->componentType === 'images_and_text')
                @include('components.blocks.images-and-text', array_merge((array)$block, $contentBlockClasses['images_and_text']?? []))
            @elseif ($block->componentType === 'video')
                <section class="section">
                    <div class="
                        section-row
                        section-row--padded
                        section-row--sm
                        section-row--center
                    ">
                        <h2 class='section-title section-title--section-margin'>{{ $block->heading }}</h2>
                        @include('components.blocks.video', (array)$block)
                        @include('components.base.paragraphs', [ 'paragraphs' => $block->paragraphs ])
                    </div>
                </section>
            @endif
        </div>
    @endif
@endforeach
