@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        @include('components.blocks.overviews', (array)$page->content->overviews)
        @include('components.blocks.pull-quote', [
            'quote' => $page->content->quote->quote ?? null,
            'attribution' => $page->content->quote->attribution ?? null,
            'image' => $page->content->quote->image ?? null,
        ])
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
