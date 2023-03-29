@extends('layouts.flexible-page-cms')

@section('content')
    @component('components.blocks.page-hero', (array)$page->content->hero)@endcomponent
    <main class="doc-content">
      @component('components.blocks.page-intro', (array)$page->content->intro)@endcomponent
      <div class="wrapper wrapper--blue">
        <section class="section">
          <div class="
            section-row
            section-row--padded
            section-row--center
          ">
            <h2 class='section-title section-title--no-margin'>{{$page->content->main->heading}}</h2>
          </div>
          <div class="
            section-row
            section-row--padded-bottom
            section-row--center
          ">
            @include('components.blocks.img-row', [
              'images' => (array)$page->content->main->topImages,
              ])
          </div>
          <div class="
            section-row
            section-row--sm
          ">
            @include('components.base.wysiwyg', ['content' => $page->content->main->topWysiwyg])
          </div>
          <!-- /WYSIWYG Content -->
          <div class="
            section-row
            section-row--padded
            section-row--center
          ">
            @foreach($page->content->main->middleImages as $image)
              <img class="img-framed" src="{{ asset($image) }}" alt="History">
            @endforeach
          </div>
          <div class="
            section-row
            section-row--sm
          ">
            @include('components.base.wysiwyg', ['content' => $page->content->main->bottomWysiwyg])
          </div>
          <div class="
            section-row
            section-row--padded
            section-row--center
          ">
            <div class="flex-row">
              @foreach($page->content->main->bottomImages as $image)
                <div class="flex-row__col">
                  <img class="img-framed" src="{{ asset($image) }}" alt="History">
                </div>
              @endforeach
            </div>
          </div>
        </section>
      </div>
      @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
