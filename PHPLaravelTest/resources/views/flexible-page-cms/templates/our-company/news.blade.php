@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        <div class="wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                ">
                    @include('components.blocks.news-items', [
                        'heading' => $page->content->latestStories->heading,
                        'items' => $page->content->latestStories->items,
                    ])
                </div>
            </section>
        </div>
        @include('components.blocks.pull-quote', [
            'quote' => $page->content->quote->quote,
            'attribution' => $page->content->quote->attribution,
            'image' => $page->content->quote->image,
        ])
        <div class="wrapper wrapper--blue">
            @include('components.blocks.press-center', [
                'pressCenter' => $page->content->pressCenter
            ])
        </div>
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
