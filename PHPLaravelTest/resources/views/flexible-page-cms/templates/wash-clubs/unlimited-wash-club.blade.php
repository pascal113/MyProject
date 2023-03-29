@extends('layouts.flexible-page-cms')

@include('components.blocks.fbq-view-products', [
    'category' => $page->title,
    'products' => $page->content->purchasingOptions->products,
])

@section('content')
    @include('components.blocks.page-hero',  (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        <div class="wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--center
                ">
                    @include('components.blocks.img-row', [
                        'images' => $page->content->main->images,
                    ])
                </div>
                <div class="
                    section-row
                    section-row--padded
                ">
                    <div class="flex-row">
                        <div class="flex-row__col align-left-desktop">
                            <h2 class='section-title section-title--margin-top'>{{ $page->content->main->heading }}</h2>
                            @include('components.base.paragraphs', ['paragraphs' => $page->content->main->paragraphs])
                            <p class='section-subtitle section-subtitle--bold'>
                                {{ $page->content->main->callToAction }}
                            </p>
                        </div>
                        <div class="flex-row__col">
                            <div class="bear-hard-hat">
                                <img src="{{ asset('images/wash-clubs/chauffeur-bear.png') }}" alt="Market">
                                <div class="speech-bubble"><span>Psst! If you’re an Uber, Lyft or Taxi driver check out our <a
                                    href="{{ cms_route('wash-clubs/for-hire-unlimited-wash-club') }}">For Hire Unlimited Wash Club</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div id="UnlimitedWashClub" class="wrapper text-center">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--sm
                ">
                    <a href="#UnlimitedWashClub"
                       class="scroll-down-icon scroll-down-icon--section-divider scroll-fade-top"></a>
                    <h2 class='section-title'>{{ $page->content->washClubLevels->heading }}</h2>
                    <div class="section-intro">
                        @include('components.base.paragraphs', ['paragraphs' => $page->content->washClubLevels->paragraphs])
                    </div>
                    <div class="
                        section-row
                        section-row--padded
                        section-row--sm
                    ">
                        @include('components.blocks.wash-levels', [
                            'items' => $page->content->washClubLevels->levels,
                        ])
                    </div>
                    <div class="
                        section-row
                        section-row--padded-bottom
                        section-row--sm
                        section-intro
                        section-intro--bold
                    ">
                        @include('components.base.paragraphs', ['paragraphs' => $page->content->washClubLevels->paragraphs2])
                    </div>
                </div>
            </section>
        </div>
        <div id="PurchasingOptions" class="wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-top
                    section-row--sm
                    section-row--center
                    section-intro
                ">
                    <a href="#PurchasingOptions"
                       class="scroll-down-icon scroll-down-icon--section-divider scroll-fade-top"></a>
                    <h2 class='section-title section-title--section-margin'>{{ $page->content->purchasingOptions->heading }}</h2>
                    @include('components.base.paragraphs', ['paragraphs' => $page->content->purchasingOptions->paragraphs])
                </div>
                <div class="
                    section-row
                    section-row--padded
                ">
                    @include('components.blocks.products', ['products' => $page->content->purchasingOptions->products])
                </div>
            </section>
        </div>
        <div id="FindTunnelWash" class="wrapper">
            <section class="section section--center section--sm">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                    section-intro
                ">
                    <a href="#FindTunnelWash"
                       class="scroll-down-icon scroll-down-icon--section-divider scroll-fade-top"></a>
                    <h2 class='section-title'>{{ $page->content->findTunnelWash->heading }}</h2>
                    @include('components.base.paragraphs', ['paragraphs' => $page->content->findTunnelWash->paragraphs])
                    @if (isset($page->content->findTunnelWash->image))
                    <div class="map-with-pin">
                        <a
                            href="#"
                            class="
                                icon-link
                                icon-link--pin
                                icon-link--pin--large
                        "></a>
                            @if(isset($page->content->findTunnelWash->image))
                                <img class="map-with-pin__map" src="{{ $page->content->findTunnelWash->image }}"
                                    alt="$page->content->findTunnelWash->heading">
                            @endif
                    </div>
                    @endif
                    @if($page->content->findTunnelWash->button ?? null)
                        @include('components.base.button', (array) $page->content->findTunnelWash->button)
                    @endif
                </div>
            </section>
        </div>
        @component('components.blocks.wash-green', [
            'button' => $page->content->washGreen->button,
        ])
            @slot('heading')
                {{ $page->content->washGreen->heading }}
            @endslot
            @slot('paragraphs')
                @include('components.base.wysiwyg', ['content' => $page->content->washGreen->wysiwyg])
            @endslot
        @endcomponent
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
