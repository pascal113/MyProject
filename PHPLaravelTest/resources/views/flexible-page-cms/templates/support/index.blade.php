@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        <div class="wrapper wrapper--blue">
            <section class="section">
                <div class="
              section-row
              section-row--padded-top
              section-row--center
              section-row--sm
            ">
                    <h2 class='section-title'>{{ $page->content->faq->heading }}</h2>
                    <div class="remove-last-margin section-intro">
                        @include('components.base.paragraphs', ['paragraphs' => $page->content->faq->paragraphs])
                    </div>
                </div>
                <div class="
              section-row
              section-row--padded
              section-row--center
              section-row--sm
            ">
                    @include('components.blocks.faq-questions', ['questions' => $page->content->faq->items])
                </div>
            </section>
        </div>
        @include('components.blocks.contact', (array)$page->content->contactUs)
    </main>
@endsection
