@extends('layouts.flexible-page-cms')

@section('content')
    @include('components.blocks.page-hero',(array)$page->content->hero)
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
              @if(!empty($page->content->pressContacts->icon))
                <i class="icon icon--{{ $page->content->pressContacts->icon }}"></i>
              @endif
              <h2 class='section-title section-title--margin-top'>{{ $page->content->pressContacts->heading }}</h2>
              <div class="contact-large">
                <p>{{ $page->content->pressContacts->email }}<br>
                    {{ $page->content->pressContacts->phoneNumber }}</p>
              </div>
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
              <h2 class='section-title section-title--margin-top'>Send a Press Center Inquiry</h2>
                @include('components.blocks.contact-email', [
                    'mailto' => config('mail.marketing.address'),
                    'subject' => 'Press Inquiry',
                    'showOptions' => false,
                ])
            </div>
          </section>
      </div>
      @include('components.blocks.ask-a-question', (array)$page->content->askAQuestion)
    </main>
@endsection
