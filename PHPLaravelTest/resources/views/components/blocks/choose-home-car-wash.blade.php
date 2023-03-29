<h3 class="section-title">Choose Your Home Car Wash</h3>
<hr />
<p class="width-small page-intro">{{ $intro }}</p>
<div
    id="choose-home-car-wash"
    class="section-row section-row--sm"
>
    <choose-home-car-wash
        inline-template
        ref="chooseHomeCarWash"
        map-api-key="{{ config('services.googlemaps.key') }}"
    >
        <div>
            <div class="form-row">
                <div class="field-wrapper">
                    <label for="findLocation">Enter a Location<span v-if="loading" class="loading-indicator loading" style="height: 22px;"></span></label>
                    <div class="location-field-wrap">
                        <input
                            ref="autocomplete"
                            name="place"
                            type="text"
                            v-model="state.place"
                            v-on:keypress="onPlaceKeypress($event)"
                            class="location-field @error('location_id') has-error @enderror"
                        />
                        <button
                            type="button"
                            v-if="navigationSupported"
                            id="find-my-location"
                            v-on:click="getMyLocation($event)"
                            :class="['icon icon--small icon--scope', { 'icon--loading': loadingMyLocation }]"
                        ></button>
                    </div>

                    @error('location_id')
                        <span class="error-text invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="field-wrapper">
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
            <div
                v-if="state.locations.length"
                class="radio-group"
            >
                <h4 class="section-subtitle">Choose One<span v-if="loading" class="loading-indicator loading" style="height: 22px; position: absolute;"></span></h4>

                <label
                    v-for="location in state.locations"
                    v-bind:key="location.id"
                    class="radio-group__item"
                    :for="`location-${location.id}`"
                >
                    <div class="radio-group__radio"><input type="radio" :id="`location-${location.id}`" name="location_id" :value="location.id" />@{{ location.address_line_1 }}, @{{ location.address_line_2 }}</div>
                    <div class="radio-group__detail">@{{ Math.round(location.miles_distance * 10) / 10 }} miles</div>
                </label>
            </div>
            <div v-if="!state.locations.length && latLngExists && !loading">
                <p class="radio-group__no-results">Oops! There are no car washes in this area. A home car wash is required to purchase a wash club. Please enter another zip code or remove the Unlimited Wash Club membership from your cart.</p>
            </div>
        </div>
    </choose-home-car-wash>
</div>

@push('js')
    <script>
        function chooseHomeCarWashInitAutocomplete() {
            window.fireVueMethod('chooseHomeCarWash', 'initPlacesAutocomplete')
        }

        Vue.component('choose-home-car-wash', {
            props: {
                mapApiKey: {
                    type: String,
                    required: true,
                },
                radius: {
                    type: Number,
                    default: 5,
                },
                services: {
                    type: Array,
                    default: function() { return [ 'tunnel-car-wash' ] },
                },
            },

            data: function() {
                return {
                    allLocations: [],
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
                this.state.radius = this.radius;
                this.selectedServices = this.services;

                this.filterLocations();

                const callbackName = 'chooseHomeCarWashInitAutocomplete'

                const gMapScript = document.createElement('script')
                gMapScript.setAttribute(
                    'src',
                    `https://maps.googleapis.com/maps/api/js?key=${this.mapApiKey}&libraries=places&callback=${callbackName}`,
                )
                gMapScript.setAttribute('async', '')
                gMapScript.setAttribute('defer', '')
                document.head.appendChild(gMapScript)

                this.loading = false;
            },

            watch: {
                selectedServices: function(services) {
                    this.filterLocations();
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

                onPlaceChange: function(autocomplete) {
                    var self = this
                    return function() {
                        var place = autocomplete.getPlace();

                        if (place) {
                            self.state.place = place.formatted_address;

                            self.latLng = {
                                lat: place.geometry.location.lat(),
                                lng: place.geometry.location.lng(),
                            };

                            self.updateLocationsByDistance();
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
                        this.updateLocationsByDistance();
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

                            self.updateLocationsByDistance();

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
                }
            },
        });
    </script>
@endpush
