@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        @include('flexible-page-cms.components.content_blocks', [ 'blocks' => $page->content->contentBlocks ?? []])
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
