@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        <div class="wrapper wrapper--blue" >
            @include('components.blocks.current-openings', (array)$page->content->openings)
        </div>
        @if ($page->content->companyBenefits->heading || $page->content->companyBenefits->videoUrl || $page->content->companyBenefits->wysiwyg)
            <div class="wrapper">
                <section class="section">
                    @if ($page->content->companyBenefits->heading)
                        <div class="
                            section-row
                            section-row--padded
                            section-row--sm
                            section-row--center
                        ">
                            <h2 class='section-title section-title--no-margin'>{{ $page->content->companyBenefits->heading }}</h2>
                        </div>
                    @endif
                    @if ($page->content->companyBenefits->videoUrl || $page->content->companyBenefits->wysiwyg)
                        <div class="
                            section-row
                            section-row--padded-bottom
                            section-row--sm
                        ">
                            @if ($page->content->companyBenefits->videoUrl ?? null)
                                @include('components.blocks.video', [ 'videoUrl' => $page->content->companyBenefits->videoUrl ])
                            @endif
                            @include('components.base.wysiwyg', ['content' => $page->content->companyBenefits->wysiwyg])
                        </div>
                    @endif
                </section>
            </div>
        @endif
        @include('components.blocks.pull-quote', [
            'quote' => $page->content->quote->quote,
            'attribution' => $page->content->quote->attribution,
            'image' => $page->content->quote->image,
        ])
        <div class="wrapper wrapper--blue">
            <section class="section">
            <div class="
                section-row
                section-row--padded-top
                section-row--sm
                section-row--center
            ">
                <h2 class='section-title section-spacing-below'>{{ $page->content->employeeSupport->heading }}</h2>
                @include('components.base.wysiwyg', [
                    'content' => $page->content->employeeSupport->wysiwyg,
                    'class' => 'recognition-and-support-wysiwyg-container',
                ])
            </div>
            <div class="
                section-row
                section-row--padded-bottom
                section-row--center
            ">
                @if ($page->content->employeeSupport->button ?? null)
                    @include('components.base.button', array_merge((array) $page->content->employeeSupport->button, ['wrapperClass' => 'button-row button-row--no-margin']))
                @endif
            </div>
            </section>
        </div>
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
