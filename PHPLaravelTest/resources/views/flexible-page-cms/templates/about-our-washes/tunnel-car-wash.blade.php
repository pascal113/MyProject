@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero',  (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        @include('flexible-page-cms.components.content_blocks', [ 'blocks' => $page->content->contentBlocks ?? []])
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                ">
                    @include('components.blocks.products', ['products' => $page->content->products->items])
                </div>
            </section>
        </div>
        @include('components.blocks.wash-green', (array)$page->content->washGreen)
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
