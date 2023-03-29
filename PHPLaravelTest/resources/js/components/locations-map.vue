<template>
    <div>
        <div id="locations-map" />
    </div>
</template>

<script>
const styles = require('../google-map-styles').default

export default {
    props: {
        apiKey: {
            type: String,
            required: true,
        },
        defaultCenter: {
            type: Object,
            required: true,
        },
        locations: {
            type: Array,
            required: true,
        },
        parentRef: {
            type: String,
            default: 'app',
        },
        zoom: {
            type: Number,
            required: true,
        },
    },

    data() {
        return {
            bounds: null,
            map: null,
            markers: [],
            onAfterLocationsUpdated: null,
            resizeDebounceTimeout: null,
            state: { locations: [] },
        }
    },

    watch: {
        locations: function(locations, prevLocations) {
            if (
                JSON.stringify(locations.map(location => location.id)) !==
                JSON.stringify(prevLocations.map(location => location.id))
            ) {
                this.state.locations = locations

                if (this.map) {
                    if (this.markers) {
                        this.markers.map(marker => {
                            marker.setMap(null)

                            this.state.locations = this.state.locations.map(location => {
                                if (marker.metadata.locationId === location.id) {
                                    marker.setMap(this.map)
                                    location.isOnMap = true
                                }

                                return location
                            })
                        })
                    }

                    this.state.locations = this.state.locations.map(location => {
                        if (!location.isOnMap) {
                            this.addMarker(location)
                        }
                    })
                }
            }

            this.onAfterLocationsUpdated && this.onAfterLocationsUpdated()
        },
    },

    created() {
        window.addEventListener('resize', this.onWindowResize)
    },

    destroyed() {
        window.removeEventListener('resize', this.onWindowResize)
    },

    mounted() {
        this.state.locations = this.locations

        const callbackName = `${this.parentRef}.$refs.locationMap.initMap`

        const gMapScript = document.createElement('script')
        gMapScript.setAttribute(
            'src',
            `https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&libraries=places&callback=${callbackName}`,
        )
        gMapScript.setAttribute('async', '')
        gMapScript.setAttribute('defer', '')
        document.head.appendChild(gMapScript)
    },

    methods: {
        initMap: function() {
            // Create map
            this.map = new window.google.maps.Map(document.querySelector('#locations-map'), { styles })

            // Create markers
            this.markers = this.state.locations.map(location => this.addMarker(location))

            // Set up info windows
            this.markers.map(marker =>
                marker.addListener('click', function() {
                    if (this.map.activeInfowindow) {
                        this.map.activeInfowindow.close()
                    }

                    const infowindow = new window.google.maps.InfoWindow({ content: marker.metadata.infoWindowHtml })

                    infowindow.open(this.map, marker)

                    this.map.activeInfowindow = infowindow
                }),
            )

            this.centerMapOnLatLngAndMarkers()

            if (window.app.$refs.findAWash) {
                window.app.$refs.findAWash.initPlacesAutocomplete()
            }
        },

        clearBounds: function() {
            this.bounds = new window.google.maps.LatLngBounds()
        },

        addMarker: function(location) {
            const position = new window.google.maps.LatLng(location.lat, location.lng)

            return new window.google.maps.Marker({
                metadata: {
                    locationId: location.id,
                    infoWindowHtml: location.map_info_window_html,
                },
                position,
                map: this.map,
            })
        },

        centerMapOnLatLngAndMarkers: function(latLng = null, padding = 10) {
            if (!this.map) {
                return
            }

            this.clearBounds()

            const latLngExists = (latLng && latLng.lat && latLng.lng && true) || false

            const activeMarkers = this.markers.filter(marker => marker.map)
            if (activeMarkers.length > 1 || (latLngExists && activeMarkers.length)) {
                // Fit to bounds around mutiple markers
                activeMarkers.map(marker => {
                    this.addLatLngToBounds(marker.position.lat(), marker.position.lng())
                })

                if (latLngExists) {
                    this.addLatLngToBounds(latLng.lat, latLng.lng)
                }

                this.map.fitBounds(this.bounds, padding)
            }
            else {
                // Center/zoom to single marker
                const firstActiveMarkerLatLng =
                    (activeMarkers.length && {
                        lat: activeMarkers[0].position.lat(),
                        lng: activeMarkers[0].position.lng(),
                    }) ||
                    null
                const center =
                    (latLngExists && latLng) || firstActiveMarkerLatLng || this.defaultCenter
                const zoom =
                    (latLngExists && this.zoom) || (activeMarkers.length && this.zoom) || 10

                this.map.setZoom(zoom)
                this.map.panTo(new window.google.maps.LatLng(center.lat, center.lng))
            }
        },

        addLatLngToBounds: function(lat, lng) {
            const position = new window.google.maps.LatLng(lat, lng)

            this.bounds.extend(position)
        },

        centerMapOnLatLng: function(lat, lng, zoom = 15) {
            this.map.setZoom(zoom)
            this.map.panTo(new window.google.maps.LatLng(lat, lng))
        },

        onWindowResize: function() {
            if (this.resizeDebounceTimeout) clearTimeout(this.resizeDebounceTimeout)
            this.resizeDebounceTimeout = setTimeout(() => {
                this.centerMapOnLatLngAndMarkers()
            }, 300)
        },

        scrollWindowToMap: function() {
            const top = document.querySelector('#locations-map').getBoundingClientRect().top - 300

            if (top < 0) {
                window.scrollBy({
                    top,
                    behavior: 'smooth',
                })
            }
        },

        openDirectionsInNewTab: function(lat, lng) {
            window.open(`https://www.google.com/maps/dir//${lat},${lng}/`, '_blank')
        },
    },
}
</script>
