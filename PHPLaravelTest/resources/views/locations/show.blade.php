@extends('layouts.web')

@push('fbq')
    <script>
        fbq('track', 'FindLocation')
    </script>
@endpush

@section('content')
    @component('components.blocks.page-hero', [
        'image' => asset($location->hero_image ?? 'images/page-hero-bgrs/find-a-wash-detail.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper">
          <section class="section section--xl">
            <div class="
              section-row
              section-row--padded
            ">
              <div class="location-layout">
                <!-- Begin Map -->
                <div class="location-layout__map">
                    <noscript style="display: block; width: 100%; height: 100px;">Please enable javascript. This page requires it in order to work properly.</noscript>

                    @php
                        $locations = collect([$location]);
                    @endphp
                    <locations-map
                        ref="locationMap"
                        parent-ref="app"
                        api-key="{{ config('services.googlemaps.key') }}"
                        :locations='@json($locations)'
                        :zoom={{ config('services.googlemaps.zoom') }}
                        :default-center='{ lat: {{ config('services.googlemaps.center.lat') }}, lng: {{ config('services.googlemaps.center.lng') }} }'
                    />
                </div>
                <!-- End Map -->
                <!-- Location List -->
                <div class="location-layout__list">
                  <h1 class='page-title'>{{ $location->title }}</h1>
                  <!-- Location Detail -->
                  <div class="location-detail">
                    <div class="location-detail__top">
                      <ul>
                        <li data-test-id="location-detail:addr-line-one">{{ $location->address_line_1 }}</li>
                        <li data-test-id="location-detail:addr-line-two">{{ $location->address_line_2 }}</li>
                        <li class="location-detail__phone">{{ $location->phone }}</li>
                        @if ($location->temporarily_closed)
                            <li class="location-detail__temporarily-closed">Temporarily Closed</li>
                        @endif
                      </ul>
                    </div>
                    <ul class="location-detail__services">
                      @foreach ($location->services as $service)
                        <li><i class="icon icon--small icon--{{ $service->icon_class }}"></i> {{ $service->name }} <a href='#{{ $service->slug }}'>{{ $service->isOpen ? 'Open' : 'Closed' }}</a></li>
                      @endforeach
                    </ul>
                    <div class="button-row">
                      <button type="button" class="button" onClick="app.$refs.locationMap.openDirectionsInNewTab({{$location->lat}}, {{$location->lng}});">Map It</button>
                    </div>
                  </div>
                  <!-- End Location Detail -->
                </div>
                <!-- End Location List -->

                @if ($location->services->count())
                    <!-- More About -->
                    <div class="location-layout__about">
                        <h2 class="section-title">More About this Wash</h2>

                        @foreach ($location->services as $service)
                            <div class="location-hours">
                                <a id="{{ $service->slug }}"></a>

                                <h3 class="section-subtitle"><i class="icon icon--small icon--{{ $service->icon_class }}"></i>{{ $service->name }} Hours</h3>
                                <dl class="location-hours__list">
                                    @if ($location->temporarily_closed)
                                        <p class="location-detail__temporarily-closed">Temporarily Closed</p>
                                    @endif

                                    @if ($service->is247)
                                        <dt>Daily</dt>
                                        <dd>24 Hours</dd>
                                    @else
                                        @for ($isoWeekday = 1; $isoWeekday <= 7; $isoWeekday++)
                                            <dt>{{ ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][$isoWeekday - 1] }}</dt>
                                            @if ($service->pivot->{'day_'.$isoWeekday.'_opens_at'} === 0 && $service->pivot->{'day_'.$isoWeekday.'_closes_at'} === 86400)
                                                <dd>24 Hours</dd>
                                            @elseif ($service->pivot->{'day_'.$isoWeekday.'_opens_at'} === null && $service->pivot->{'day_'.$isoWeekday.'_closes_at'} === null)
                                                <dd>Closed</dd>
                                            @else
                                                <dd>{{ \Carbon\Carbon::parse((int)$service->pivot->{'day_'.$isoWeekday.'_opens_at'})->format(config('format.time')) }} - {{ \Carbon\Carbon::parse((int)$service->pivot->{'day_'.$isoWeekday.'_closes_at'})->format(config('format.time')) }}</dd>
                                            @endif
                                        @endfor
                                    @endif
                                </dl>
                            </div>
                        @endforeach
                    </div>
                    <!-- End More About -->
                @endif

                <div class="location-layout__about">
                    @if ($location->manager_name)
                        <h3 class="section-subtitle section-subtitle--no-margin">{{ $location->manager_title ? Str::title($location->manager_title) : 'Manager' }}</h3>
                        <span class="location-manager">{{ $location->manager_name }} <a href="{{ cms_route('/our-company/leadership').'#'.Str::slug(trim($location->manager_name)) }}" class="link-black">View Profile ></a></span>
                    @endif
                </div>

                <div class="location-layout__about">
                    <p>
                        <a href="#" class="button button--icon button--chat" data-featherlight="#feedback" data-featherlight-persist="true" dusk="send-feedback" data-test-id="send-feedback">Send Feedback</a>
                        @include('components.popups.feedback', [
                            'location' => $location,
                        ])
                    </p>
                </div>
              </div>
            </div>
          </section>
        </div>
        @if ($nearbyLocations->count())
          <div class="block-nearby-washes">
            <div class="block-nearby-washes__content">
              <h2 class="section-title">Other Nearby Washes</h2>
              @foreach ($nearbyLocations as $nearbyLocation)
                <!-- Location Detail -->
                <div class="location-detail">
                  <div class="location-detail__top">
                    <span class="location-detail__distance">{{ round($nearbyLocation->miles_distance, 1) }} miles</span>
                    <ul>
                        <li class="location-detail__title"><a href='{{ $nearbyLocation->url }}'>{{ $nearbyLocation->title }}</a></li>
                        <li>{{ $nearbyLocation->address_line_1 }}</li>
                        <li>{{ $nearbyLocation->address_line_2 }}</li>
                        <li class="location-detail__phone">{{ $nearbyLocation->phone }}</li>
                    </ul>
                  </div>
                  @if ($nearbyLocation->services->count())
                    <ul class="location-detail__services">
                      @foreach ($nearbyLocation->services as $service)
                        <li><i class="icon icon--small icon--{{ $service->icon_class }}"></i> {{ $service->name }} <a href='{{ $nearbyLocation->url . "#" . $service->slug }}'>Open</a></li>
                      @endforeach
                    </ul>
                  @endif
                  <div class="button-row">
                    <button class="button" onClick="app.$refs.locationMap.openDirectionsInNewTab({{$nearbyLocation->lat}}, {{$nearbyLocation->lng}});">Map It</button>
                  </div>
                </div>
              <!-- End Location Detail -->
              @endforeach
          </div>
        </div>
      @endif
    </main>
@endsection
