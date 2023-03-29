@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        @include('flexible-page-cms.components.content_blocks', ['blocks' => $page->content->contentBlocks ?? []])
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--sm
                    section-row--center
                    section-intro
                ">
                    <h2 class='section-title section-title--section-margin'>{{ $page->content->participatingDealerships->heading }}</h2>
                    @include('components.base.paragraphs', ['paragraphs' => $page->content->participatingDealerships->paragraphs])
                    <h3 class='section-subtitle section-subtitle--bold'>{{ $page->content->participatingDealerships->subHeading }}</h3>
                    @if ($page->content->participatingDealerships->image)
                        <img src="{{ asset($page->content->participatingDealerships->image) }}"
                            alt="{{ htmlspecialchars($page->content->participatingDealerships->subHeading) }}"
                            class="Participating Dealerships Tickets">
                    @endif
                </div>
                <div class="
                    section-row
                    section-row--padded-bottom
                    section-row--center
                ">
                    <h3 class='section-subtitle section-subtitle--bold'>{{ $page->content->participatingDealerships->dealershipsSubheading }}</h3>
                    <div class="block-dealer-logos">
                        @foreach ($page->content->participatingDealerships->dealershipLogos->items ?? [] as $key => $item)
                            @if (isset($item->image))
                                <div class="{{$key > 7 ? 'hidden' : ''}}">
                                    @if (!empty($item->url->value))
                                        <a href="{{ $item->url->value }}" {{ (isset($item->url->openInNewTab) && $item->url->openInNewTab) ? 'target="_blank"' : ''}}>
                                            <img src="{{ asset($item->image) }}" alt="">
                                        </a>
                                    @else
                                        <img src="{{ asset($item->image) }}" alt="">
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if (count($page->content->participatingDealerships->dealershipLogos->items ?? []) > 8)
                        <div class="button-row">
                            <button class="button" id="showMoreDealers" >Show More Dealers</button>
                        </div>
                    @endif
                </div>
            </section>
        </div>
        @include('components.blocks.getting-started', (array)$page->content->programInfo)
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('#showMoreDealers').click(function () {
                var dealerImages = $('.block-dealer-logos > .hidden');
                if (dealerImages.length > 0) {
                    $(this).text('Show Less Dealers');
                    dealerImages.removeClass('hidden');
                    dealerImages.addClass('visible');
                } else {
                   dealerImages = $('.block-dealer-logos > .visible');
                    $(this).text('Show More Dealers');
                    dealerImages.addClass('hidden');
                    dealerImages.removeClass('visible');
                }
            })
        });
    </script>
@endpush
