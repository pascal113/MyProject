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
                    section-row--sm
                ">
                    <h2 class='section-title'>{{ $page->content->ourHistory->heading }}</h2>
                    @if ($page->content->ourHistory->image)
                        <img class="img-framed img-framed--has-margin" src="{{ asset($page->content->ourHistory->image) }}" alt="">
                    @endif
                    @include('components.base.paragraphs', ['paragraphs' => $page->content->ourHistory->paragraphs])
                    @if ($page->content->ourHistory->button ?? null)
                        @include('components.base.button', array_merge((array) $page->content->ourHistory->button, ['wrapperClass' => 'button-row button-row--no-margin']))
                    @endif
                </div>
            </section>
        </div>
        <div class="wrapper">
            @include('components.blocks.leadership', (array)$page->content->leadership)
        </div>
        <div class="wrapper wrapper--blue">
            <section class="section ">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title'>{{ $page->content->careers->heading }}</h2>
                    @if (isset($page->content->careers->image))
                        <img class="img-framed img-framed--has-margin" src="{{ asset($page->content->careers->image) }}" alt="Careers">
                    @endif
                    @include('components.base.paragraphs', ['paragraphs' => $page->content->careers->paragraphs])
                    @if ($page->content->careers->button ?? null)
                        @include('components.base.button', array_merge((array) $page->content->careers->button, ['wrapperClass' => 'button-row button-row--no-margin']))
                    @endif
                </div>
            </section>
        </div>
        <div class="wrapper">
            <div class="
                section-row
                section-row--padded
                section-row--center
            ">
                @include('components.blocks.news-items', array_merge(
                    (array)$page->content->inTheNews,
                    [
                        'items' => $page->content->inTheNews->items,
                        'max' => 3,
                        'showMore' => false,
                    ]
                ))
                @if ($page->content->inTheNews->button ?? null)
                    @include('components.base.button', array_merge((array) $page->content->inTheNews->button, ['wrapperClass' => 'button-row button-row--no-margin']))
                @endif
            </div>
        </div>
        <div class="wrapper wrapper--blue">
            @include('components.blocks.press-center', ['pressCenter' => $page->content->pressCenter])
        </div>
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>

@endsection
