@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero', (array)$page->content->hero)
    <main class="doc-content">
        @include('components.blocks.page-intro', (array)$page->content->intro)

        @foreach ([
            'corporateExecutivesSection',
            'seniorManagersSection',
            'areaManagersSection',
            'siteManagersSection',
        ] as $i => $type)
            @if (isset($page->content->{$type}) && count($page->content->{$type}->people))
                <div class="wrapper{{ $i % 2 === 0 ? ' wrapper--blue' : '' }}">
                    <section class="section">
                        <div class="
                            section-row
                            section-row--sm
                            section-row--padded
                            section-row--center
                        ">
                            <h2 class='section-title'>{{ $page->content->{$type}->heading }}</h2>
                            @include('components.base.paragraphs', [
                                'paragraphs' => $page->content->{$type}->paragraphs,
                                'class' => 'section-intro make-trademarks-superscript',
                            ])
                        </div>
                        <div class="
                            section-row
                            section-row--padded-bottom
                        ">
                            @include('components.blocks.people', [
                                'people' => $page->content->{$type}->people,
                            ])
                        </div>
                    </section>
                </div>
            @endif
        @endforeach

        @if ($page->content->joinOurTeam->videoUrl ?? null || $page->content->joinOurTeam->heading ?? null || $page->content->joinOurTeam->paragraphs ?? null || $page->content->joinOurTeam->button ?? null)
            <div class="wrapper">
                <section class="section">
                    <div class="
                        section-row
                        section-row--sm
                        section-row--padded
                        section-row--center
                    ">
                        <h2 class='section-title section-title--no-margin'>{{ $page->content->joinOurTeam->heading }}</h2>
                    </div>
                    <div class="
                        section-row
                        section-row--padded-bottom
                        section-row--sm
                        section-row--center
                    ">
                        @if ($page->content->joinOurTeam->videoUrl ?? null)
                            @include('components.blocks.video', [ 'videoUrl' => $page->content->joinOurTeam->videoUrl ])
                        @endif
                        @include('components.base.paragraphs', [
                            'paragraphs' => $page->content->joinOurTeam->paragraphs,
                            'class' => 'make-trademarks-superscript',
                        ])
                        @if ($page->content->joinOurTeam->button ?? null)
                            @include('components.base.button', array_merge((array) $page->content->joinOurTeam->button), ['wrapperClass' => 'button-row--no-margin'])
                        @endif
                    </div>
                </section>
            </div>
        @endif
        @include('components.blocks.ask-a-question', array_merge((array)$page->content->askAQuestion, [ 'class' => 'bgr-grey' ]))
    </main>
@endsection
