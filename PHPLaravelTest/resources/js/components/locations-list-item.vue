<template>
    <div class="location-detail">
        <div class="location-detail__top">
            <span
                v-if="milesDistance !== null"
                class="location-detail__distance"
            >{{ milesDistance }} miles</span>
            <ul>
                <li class="location-detail__title">
                    <a data-test-class="location-list-item" :href="url">{{ title }} </a>
                </li>
                <li data-test-class="address-line-one">{{ addressLine1 }}</li>
                <li data-test-class="address-line-two">{{ addressLine2 }}</li>
                <li class="location-detail__phone">
                    {{ phone }}
                </li>
                <li
                    v-if="temporarilyClosed"
                    class="location-detail__temporarily-closed"
                >
                    Temporarily Closed
                </li>
            </ul>
        </div>
        <ul class="location-detail__services">
            <li
                v-for="service in services"
                :key="service.id"
            >
                <i :class="`icon icon--small icon--${service.icon_class}`" />
                {{ service.name }}
                <a :href="`${url}#${service.slug}`">{{ service.isOpen ? 'Open' : 'Closed' }}</a>
            </li>
        </ul>
        <div class="button-row">
            <button
                type="button"
                class="button"
                @click.prevent.stop="mapToLocation()"
            >
                Map It
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        addressLine1: {
            type: String,
            required: true,
        },
        addressLine2: {
            type: String,
            required: true,
        },
        milesDistance: {
            type: Number,
            default: -1,
        },
        lat: {
            type: Number,
            required: true,
        },
        lng: {
            type: Number,
            required: true,
        },
        phone: {
            type: String,
            required: true,
        },
        services: {
            type: Array,
            required: true,
        },
        temporarilyClosed: {
            type: Boolean,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        url: {
            type: String,
            required: true,
        },
    },

    methods: {
        mapToLocation() {
            window.app.$refs.findAWash.$refs.locationMap.openDirectionsInNewTab(this.lat, this.lng)
        },
    },
}
</script>
