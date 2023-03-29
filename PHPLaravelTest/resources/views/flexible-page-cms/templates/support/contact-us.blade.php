@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-bottom
                    section-row--center
                    section-row--sm
                ">
                @if(!empty($page->content->phone->icon))
                    <i class="icon icon--{{ $page->content->phone->icon }}"></i>
                @endif
                <h2 class='section-title section-title--margin-top'>{{ $page->content->phone->heading }}</h2>
                @component('components.blocks.hide-show', [ 'id' => 'phone' ])
                    @include('components.blocks.contact-phone', ['items' => $page->content->phone->items])
                @endcomponent
                </div>
            </section>
        </div>
        <div class="wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                <i class="icon icon--email"></i>
                <h2 class='section-title section-title--margin-top'>Email</h2>
                @component('components.blocks.hide-show', [ 'id' => 'email' ])
                    @include('components.blocks.contact-email')
                @endcomponent
                </div>
            </section>
        </div>
        <div class="wrapper wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                @if(!empty($page->content->snailMail->icon))
                    <i class="icon icon--{{ $page->content->snailMail->icon }}"></i>
                @endif
                <h2 class='section-title section-title--margin-top'>{{ $page->content->snailMail->heading }}</h2>
                @component('components.blocks.hide-show', [ 'id' => 'snail-mail' ])
                    @include('components.blocks.contact-snail-mail', ['paragraphs' => $page->content->snailMail->paragraphs])
                @endcomponent
                </div>
            </section>
        </div>

        <div class="wrapper wrapper wrapper--blue">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    @if(!empty($page->content->mailingList->icon))
                        <i class="icon icon--{{ $page->content->mailingList->icon }}"></i>
                    @endif
                    <h2 class='section-title section-title--margin-top'>{{ $page->content->mailingList->heading }}</h2>
                    @component('components.blocks.hide-show', [ 'id' => 'mailing-list' ])
                        @include('components.blocks.contact-mailing-list', ['paragraphs' => $page->content->mailingList->paragraphs])
                    @endcomponent
                </div>
            </section>
        </div>
    </main>
@endsection
