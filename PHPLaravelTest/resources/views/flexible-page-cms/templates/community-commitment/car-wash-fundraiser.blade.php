@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        @include('flexible-page-cms.components.content_blocks', [ 'blocks' => $page->content->contentBlocks ?? []])
        <div class="wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                ">
                    <div class="row-img-left">
                        <div>
                            <img class="heart-bear img-content img-content--center"
                                 src="{{ asset('images/careers/heartbear.png') }}" alt="Heart Bear">
                        </div>
                        <div>
                            <h3 class="section-subtitle">{{ $page->content->interestedInGettingStarted->heading }}</h3>

                            @include('components.base.wysiwyg', [
                                'content' => $page->content->interestedInGettingStarted->wysiwyg,
                                'class' => 'wysiwyg-content--getting-started',
                            ])

                            @if ($page->content->interestedInGettingStarted->button ?? null)
                                @include('components.base.button', array_merge((array)$page->content->interestedInGettingStarted->button, ['wrapperClass' => 'button-row--section-margin']))
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
