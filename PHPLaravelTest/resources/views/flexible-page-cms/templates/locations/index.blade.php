@extends('layouts.flexible-page-cms', ['meta' => $page->meta])

@push('fbq')
    <script>
        fbq('track', 'FindLocation')
    </script>
@endpush

@section('content')
    @component('components.blocks.page-hero', [
        'heading' => $page->content->hero->heading,
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content" id="find-a-wash">
        <find-a-wash
            inline-template
            ref="findAWash"
            :locations='@json($locations->toArray())'
            :all-services='@json($allServices->toArray())'
            map-api-key="{{ config('services.googlemaps.key') }}"
            :map-default-center='{ lat: {{ config('services.googlemaps.center.lat') }}, lng: {{ config('services.googlemaps.center.lng') }} }'
            :map-zoom="{{ config('services.googlemaps.zoom') }}"
            @if ($place)
                place="{{ $place }}"
            @endif
            :radius="{{ $radius }}"
            @if ($services)
                :services='@json($services)'
            @endif
        >
            <div class="wrapper">
                <form method="get" action="?">
                    <section class="section section--xl">
                        <div class="section-row section-row--padded-top">
                            <div class="find-a-wash-title">
                                <h1 class="page-title">{{ $page->content->intro->heading }}<span v-if="loading" class="loading-indicator loading" style="height: 38px;"></span></h1>
                                <button @click="reset($event)" class="a">View All ></button>
                            </div>
                            <div class="find-a-wash-top" hidden>
                                <div class="find-a-wash-top__col">
                                    <label for="findLocation">Enter a Location</label>
                                    <div class="location-field-wrap">
                                        <input
                                            ref="autocomplete"
                                            class="location-field"
                                            name="place"
                                            type="text"
                                            v-model="state.place"
                                            v-on:keypress="onPlaceKeypress($event)"
                                        />
                                        <button
                                            type="button"
                                            v-if="navigationSupported"
                                            id="find-my-location"
                                            v-on:click="getMyLocation($event)"
                                            :class="['icon icon--small icon--scope', { 'icon--loading': loadingMyLocation }]"
                                         ></button>
                                    </div>
                                </div>
                                <div class="find-a-wash-top__col">
                                    <label for="selectDistance">Distance</label>
                                    <select
                                        name="radius"
                                        v-model="state.radius"
                                        @change="onRadiusChange($event)"
                                    >
                                        <option :value="5">5 Miles</option>
                                        <option :value="10">10 Miles</option>
                                        <option :value="15">15 Miles</option>
                                        <option :value="20">20 Miles</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="section section--xl">
                        <div class="section-row select-features-wrapper" hidden>
                            <span class="label-style">Select Features</span>
                            <div class="select-features">
                                <label class="checkbox inline" v-for="service in allServices" v-bind:key="service.id" :for="service.slug">
                                    <input
                                        type="checkbox"
                                        name="services[]"
                                        :value="service.slug"
                                        :id="service.slug"
                                        v-model="selectedServices"
                                        :data-test-id="`find-a-wash:filter:${service.slug}`"
                                    />
                                    <i :class="'icon icon--small icon--' + service.icon_class "></i> <span>@{{ service.name }}</span>
                                </label>
                            </div>
                        </div>
                    </section>
                </form>
                <section class="section section--xl">
                    <div class="section-row section-row--padded-bottom">
                        <div class="block-locations">
                            <noscript>Please enable javascript. This page requires it in order to work properly.</noscript>

                            <div class="block-locations__tabs tabs-js">
                                <button :class="'block-locations__tab' + (currentTab === 'list' ? ' selected' : '') " @click.prevent.stop="onTabClick('list')">List View</button>
                                <button :class="'block-locations__tab' + (currentTab === 'map' ? ' selected' : '') " @click.prevent.stop="onTabClick('map')">Map View (<span>@{{ state.locations.length }}</span>)</button>
                            </div>
                            <div class="block-locations__content tabs-content-js">
                                <div :class="'block-locations__list' + (currentTab === 'list' ? ' show' : '') ">
                                    <locations-list
                                        :loading="loading"
                                        :locations="state.locations"
                                        :radius="state.radius"
                                        :radius-has-center="latLngExists"
                                    />
                                </div>
                                <div :class="'block-locations__map' + (currentTab === 'map' ? ' show' : '') ">
                                    <locations-map
                                        ref="locationMap"
                                        parent-ref="app.$refs.findAWash"
                                        :api-key="mapApiKey"
                                        :default-center="mapDefaultCenter"
                                        :locations="state.locations"
                                        :zoom="mapZoom"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </find-a-wash>
    </main>
@endsection

@push('js')
    <script>
        Vue.component('find-a-wash', {
            props: {
                allServices: {
                    type: Array,
                    required: true,
                },
                locations: {
                    type: Array,
                    default: function() { return [] },
                },
                mapApiKey: {
                    type: String,
                    required: true,
                },
                mapDefaultCenter: {
                    type: Object,
                    required: true,
                },
                mapZoom: {
                    type: Number,
                    required: true,
                },
                place: {
                    type: String,
                    default: '',
                },
                radius: {
                    type: Number,
                    required: true,
                },
                services: {
                    type: Array,
                    default: function() { return [] },
                },
            },

            data: function() {
                return {
                    allLocations: [],
                    currentTab: 'list',
                    navigationSupported: !!navigator.geolocation,
                    latLng: {
                        lat: null,
                        lng: null,
                    },
                    loading: true,
                    loadingMyLocation: false,
                    selectedServices: [],
                    state: {
                        locations: [],
                        place: '',
                        radius: -1,
                    },
                }
            },

            computed: {
                latLngExists: function() {
                    return (this.latLng && this.latLng.lat && this.latLng.lng && true) || false;
                }
            },

            mounted: function() {
                this.allLocations = this.locations;
                this.state.place = this.place;
                this.state.radius = this.radius;
                this.selectedServices = this.services;

                this.filterLocations();

                this.loading = false;
                document.querySelector('.find-a-wash-top').hidden = false;
                document.querySelector('.select-features-wrapper').hidden = false;
            },

            watch: {
                selectedServices: function(services) {
                    this.filterLocations();
                    if (this.state.locations.length) {
                        this.queueReCenterMapAfterUpdate();
                    }
                }
            },

            methods: {
                initPlacesAutocomplete: function() {
                    var autocomplete = new google.maps.places.Autocomplete(this.$refs.autocomplete, {
                        types: ['geocode'],
                    });
                    autocomplete.setFields(['formatted_address', 'geometry']);

                    autocomplete.addListener('place_changed', this.onPlaceChange(autocomplete));
                },

                onTabClick: function(tab) {
                    if (this.currentTab === tab) {
                        return;
                    }

                    this.currentTab = tab;

                    if (tab === 'map') {
                        this.reCenterMap(-150);
                    }
                },

                onPlaceChange: function(autocomplete) {
                    var self = this
                    return function() {
                        var place = autocomplete.getPlace();

                        if (place) {
                            self.state.place = place.formatted_address || '';

                            if (place.geometry && place.geometry.location) {
                                self.latLng = {
                                    lat: place.geometry.location.lat(),
                                    lng: place.geometry.location.lng(),
                                };

                                self.updateLocationsByDistance().then(function() {
                                    self.reCenterMap();
                                });
                            }
                        }
                    };
                },

                onPlaceKeypress: function(event) {
                    if (event.which === 13) {
                        event.preventDefault();
                    }
                },

                onRadiusChange: function(event) {
                    if (this.latLng.lat && this.latLng.lng) {
                        var self = this
                        this.updateLocationsByDistance().then(function() {
                            self.reCenterMap();
                        });
                    }
                },

                getMyLocation: function(event) {
                    event.preventDefault();

                    this.loadingMyLocation = true;

                    if (!navigator.geolocation) {
                        this.endLoadingMyLocation();
                        return;
                    }

                    var self = this;
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            self.latLng = {
                                lat: parseFloat(position.coords.latitude),
                                lng: parseFloat(position.coords.longitude),
                            };

                            self.updateLocationsByDistance().then(function() {
                                self.reCenterMap();
                            });

                            // Get Place
                            new google.maps.Geocoder().geocode({ location: self.latLng }, function(results, status) {
                                if (status === 'OK') {
                                    if (results[0]) {
                                        self.state.place = results[0].formatted_address;
                                    } else {
                                        self.showError();
                                    }
                                } else {
                                    self.showError();
                                }
                            });

                            self.endLoadingMyLocation();
                        },
                        function(error) {
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    self.showError('Request for geolocation denied.');
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                case error.TIMEOUT:
                                case error.UNKNOWN_ERROR:
                                    self.showError();
                                    break;
                            }

                            self.endLoadingMyLocation();
                        }
                    );
                },

                showError: function(error) {
                    error = error || 'Unable to get location.';
                    Vue.toasted.error(error);
                },

                endLoadingMyLocation: function() {
                    this.loadingMyLocation = false;
                },

                reset: function(event) {
                    event.preventDefault();

                    this.latLng.lat = null;
                    this.latLng.lng = null;
                    this.state.place = '';
                    this.state.radius = this.radius;

                    var self = this;
                    this.updateLocationsByDistance().then(function() {
                        if (self.selectedServices.length) {
                            self.selectedServices = []; // This triggers a call to filterLocations() and reCenterMap() because we are 'watch'ing it
                        } else {
                            self.filterLocations();
                            self.reCenterMap();
                        }
                    });
                },

                updateLocationsByDistance: function() {
                    var self = this;

                    this.loading = true;

                    var url = '{{ route('api.locations.index') }}?';
                    if (this.latLng.lat && this.latLng.lng) {
                        url = url+'lat='+this.latLng.lat+'&lng='+this.latLng.lng+'&';
                    }
                    if (this.state.radius) {
                        url = url+'radius='+this.state.radius;
                    }

                    return window.axios.get(url)
                        .then(function(response) { return response.data; })
                        .then(function(locations) {
                            self.allLocations = locations.data;
                            self.filterLocations();
                            self.loading = false;
                        })
                        .catch(function() {
                            Vue.toasted.error('Unable to retrieve locations.');
                            self.loading = false;
                        });
                },

                filterLocations: function() {
                    if (!this.selectedServices.length) {
                        this.state.locations = this.allLocations;

                        return;
                    }

                    var self = this
                    this.state.locations = this.allLocations.filter(function(location) {
                        return self.selectedServices.some(function(serviceSlug) {
                            return location.services.find(function(service) {
                                return service.slug === serviceSlug;
                            });
                        })
                    });
                },

                queueReCenterMapAfterUpdate: function() {
                    var self = this

                    app.$refs.findAWash.$refs.locationMap.onAfterLocationsUpdated = function() {
                        self.reCenterMap();
                        app.$refs.findAWash.$refs.locationMap.onAfterLocationsUpdated = null;
                    };
                },

                reCenterMap: function(padding) {
                    app.$refs.findAWash.$refs.locationMap.centerMapOnLatLngAndMarkers(this.latLng, padding);
                }
            },
        });
    </script>
@endpush
