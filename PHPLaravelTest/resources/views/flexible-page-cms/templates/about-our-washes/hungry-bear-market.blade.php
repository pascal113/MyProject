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
            section-row--sm
            section-row--center
          ">
            <h2 class='section-title section-title--section-margin'>{{ $page->content->comeAndSeeUs->heading }}</h2>
            @if(isset($page->content->comeAndSeeUs->image))
                <img class="img-cooking-bear" src="{{ asset($page->content->comeAndSeeUs->image ) }}" alt="Cooking Bear">
            @endif
          </div>
          <div class="
            section-row
            section-row--padded-bottom
            section-row--center
          ">
            <img class="img-hungry-bear" src="{{ asset( $page->content->comeAndSeeUs->logo ) }}" alt="Logo Market">
          </div>
        </section>
      </div>
      @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
