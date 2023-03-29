@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.home-slider', (array)$page->content->carousel)
    <main class="doc-content">
        <div class="wrapper wrapper--blue">
            <section class="section section--center section--sm">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                    page-intro
                ">
                    <h1 class='page-title'>{{ $page->content->welcome->heading }}</h1>
                    @include('components.base.paragraphs', ['paragraphs' => $page->content->welcome->paragraphs])
                    <div class="map-with-pin">
                        <a
                        href="{{ route('locations.index') }}"
                        class="
                            icon-link
                            icon-link--pin
                            icon-link--pin--large
                        "></a>
                        <img class="map-with-pin__map" src="{{ asset('storage/Pages/images/img-map.png') }}" alt="Logo">
                    </div>
                    @if ($page->content->welcome->button ?? null)
                        @include('components.base.button', (array) $page->content->welcome->button)
                    @endif
                </div>
            </section>
        </div>
        <div class="wrapper wrapper--wash">
            <section class="section">
                <div class="
                    section-row
                    section-row--center
                    section-row--padded
                ">
                    <h2 class='section-title'>{{ $page->content->cards->heading }}</h2>
                    @if (count((array)$page->content->cards->items))
                        <div class="cards cards--three">
                            @foreach ($page->content->cards->items as $card)
                                <div class="card card--center">
                                    @if ($card->heading ?? null)
                                        <h3 class="section-subtitle">{{ $card->heading }}</h3>
                                    @endif
                                    @if (isset($card->image))
                                        <img src="{{ asset($card->image) }}" alt="{{ htmlspecialchars($card->heading ?? '') }}" class="has-border">
                                    @endif
                                    @include('components.base.paragraphs', ['paragraphs' => $card->description ?? null])
                                    @if ($card->button ?? null)
                                        @include('components.base.button', (array)$card->button)
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </div>
        @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
