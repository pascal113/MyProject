@extends('layouts.flexible-page-cms')

@include('components.blocks.fbq-view-products', [
    'category' => $page->title,
    'products' => $page->content->products->items,
])

@section('content')
    @include('components.blocks.page-hero',  (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-bottom
                    section-row--center
                ">
                    <h2 class='section-title section-title--no-margin'>{{ $page->content->products->heading }}</h2>
                    <div class="
                        section-row
                        section-row--padded-top
                    ">
                        @include('components.blocks.products', ['products' => $page->content->products->items])
                    </div>
                </div>
            </section>
        </div>
        @include('components.blocks.pull-quote', [
            'class' => 'green-quote',
            'quote' => $page->content->quote->quote,
            'attribution' => $page->content->quote->attribution,
            'image' => $page->content->quote->image,
        ])
        <div class="wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title'>{{ $page->content->relatedContent->heading }}</h2>
                    @if(isset($page->content->relatedContent->image))
                        <img class="img-framed img-content img-content--center"
                             src="{{ asset($page->content->relatedContent->image) }}"
                             alt="{{ htmlspecialchars($page->content->relatedContent->heading) }}">
                    @endif
                    <div class="remove-last-margin">
                        @include('components.base.paragraphs', ['paragraphs' => $page->content->relatedContent->paragraphs])
                    </div>
                </div>
                @if ($page->content->relatedContent->button ?? null)
                    <div class="
                        section-row
                        section-row--padded
                        section-row--center
                        section-row--sm
                    ">
                        @include('components.base.button', (array) $page->content->relatedContent->button)
                    </div>
                @endif
            </section>
        </div>
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
