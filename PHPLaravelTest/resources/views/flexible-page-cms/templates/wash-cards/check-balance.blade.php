@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        @include('components.blocks.wash-card-balance', [ 'cardNumber' => $cardNumber ?? null ])
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    @if(!empty($page->content->iconAndParagraph->icon))
                        <i class="icon icon--{{ $page->content->iconAndParagraph->icon }}"></i>
                    @endif
                    <h2 class="section-title section-title--margin-top make-trademarks-superscript">{{ $page->content->iconAndParagraph->heading }}</h2>
                    @include('components.base.paragraphs', [
                        'paragraphs' => $page->content->iconAndParagraph->paragraphs ?? null,
                        'class' => 'section-intro make-trademarks-superscript',
                    ])
                    @if ($page->content->iconAndParagraph->button ?? null)
                        @include('components.base.button', array_merge((array)$page->content->iconAndParagraph->button, ['wrapperClass' => 'button-row--section-margin']))
                    @endif
                </div>
            </section>
        </div>
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
