@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        @if ($page->content->main->heading || $page->content->main->videoUrl || $page->content->main->wysiwyg)
            @component('components.blocks.wash-green')
                @slot('heading')
                    @if ($page->content->main->heading)
                        {{ $page->content->main->heading }}
                    @endif
                @endslot
                @slot('media')
                    @if ($page->content->main->videoUrl ?? null)
                        @include('components.blocks.video', [
                            'videoUrl' => $page->content->main->videoUrl,
                            'posterUrl' => $page->content->main->videoPosterImage,
                        ])
                    @endif
                @endslot
                @slot('paragraphs')
                    @include('components.base.wysiwyg', ['content' => $page->content->main->wysiwyg])
                @endslot
            @endcomponent
        @endif
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    @if(!empty($page->content->environmentalBenefits->icon))
                        <i class="icon icon--{{ $page->content->environmentalBenefits->icon }}"></i>
                    @endif
                    <h2 class="section-title section-title--margin-top make-trademarks-superscript">{{ $page->content->environmentalBenefits->heading }}</h2>
                    @include('components.base.paragraphs', [
                        'paragraphs' => $page->content->environmentalBenefits->paragraphs ?? null,
                        'class' => 'section-intro make-trademarks-superscript',
                    ])
                    <hr>
                    @foreach($page->content->environmentalBenefits->items as $item)
                        @if ($item->image ?? null)
                            <img class="img-content img-content--center"
                                src="{{ asset($item->image) }}"
                                alt="{{ htmlspecialchars($item->heading ?? '') }}">
                        @endif
                        <h3 class='section-subtitle'>{{ $item->heading ?? '' }}</h3>
                        @include('components.base.wysiwyg', ['content' => $item->wysiwyg ?? ''])
                    @endforeach
                </div>
            </section>
        </div>
        <div class="wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                ">
                    <h2 class='section-title section-title--section-margin'>{{ $page->content->additionalEnvironmentalBenefits->heading }}</h2>
                    @if ($page->content->additionalEnvironmentalBenefits->image ?? null)
                        <img class="img-framed img-content img-content--center"
                            src="{{ asset($page->content->additionalEnvironmentalBenefits->image) }}"
                            alt="{{ htmlspecialchars($page->content->additionalEnvironmentalBenefits->heading) }}">
                    @endif
                </div>
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    <h2 class="section-subtitle make-trademarks-superscript">{{ $page->content->additionalEnvironmentalBenefits->heading1 }}</h2>
                    @include('components.base.paragraphs', [
                        'paragraphs' => $page->content->additionalEnvironmentalBenefits->paragraphs ?? null,
                        'class' => 'make-trademarks-superscript',
                    ])
                </div>
            </section>
        </div>
        @include('components.blocks.pull-quote', [
            'quote' => $page->content->quote->quote,
            'attribution' => $page->content->quote->attribution,
            'image' => $page->content->quote->image,
        ])
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
