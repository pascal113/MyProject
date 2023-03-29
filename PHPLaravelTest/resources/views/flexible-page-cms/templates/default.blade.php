@extends('layouts.flexible-page-cms')

@section('content')
    @component('components.blocks.page-hero', (array)$page->content->hero)@endcomponent
    <main class="doc-content">
        @component('components.blocks.page-intro', (array)$page->content->intro)@endcomponent

        @forelse ($page->content->main->alternatingContentBlocks ?? [] as $i => $block)
            <div class="wrapper{{ $i % 2 === 0 ? ' wrapper--blue' : ''}}">
                <section class="section">
                    @if (!empty($block->heading))
                        <div class="
                            section-row
                            section-row--padded
                            section-row--center
                            section-row--sm
                        ">
                            <h2 class='section-title section-title--margin-top'>{{ $block->heading }}</h2>
                        </div>
                    @endif

                    @if (!empty($block->image))
                        <div class="
                            section-row
                            section-row--padded{{ !empty($block->heading) ? '-bottom' : '' }}
                            section-row--center
                        ">
                            @include('components.blocks.img-row', [
                                'images' => (array)$block->image,
                            ])
                        </div>
                    @endif

                    @if (!empty($block->description))
                        <div class="
                            section-row
                            section-row--padded{{ !empty($block->heading) || !empty($block->image) ? '-bottom' : '' }}
                            section-row--sm
                        ">
                            @include('components.base.wysiwyg', ['content' => $block->description])
                        </div>
                    @endif
                </section>
            </div>
        @empty
        @endforelse

        @forelse ($page->content->main->unifiedContentBlocks ?? [] as $i => $block)
            <div class="wrapper wrapper--blue">
                <section class="section">
                    @if (!empty($block->heading))
                        <div class="
                            section-row
                            section-row--padded
                            section-row--center
                            section-row--sm
                        ">
                            <h2 class='section-title section-title--margin-top'>{{ $block->heading }}</h2>
                        </div>
                    @endif

                    @if (!empty($block->image))
                        <div class="
                            section-row
                            section-row--padded{{ !empty($block->heading) ? '-bottom' : '' }}
                            section-row--center
                        ">
                            @include('components.blocks.img-row', [
                                'images' => (array)$block->image,
                            ])
                        </div>
                    @endif

                    @if (!empty($block->description))
                        <div class="
                            section-row
                            section-row--padded{{ !empty($block->heading) || !empty($block->image) ? '-bottom' : '' }}
                            section-row--sm
                        ">
                            @include('components.base.wysiwyg', ['content' => $block->description])
                        </div>
                    @endif
                </section>
            </div>
        @empty
        @endforelse

        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
