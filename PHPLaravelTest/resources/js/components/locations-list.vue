<template>
    <div data-test-class="locations-list">
        <h3
            class="section-intro section-intro--bold"
            data-test-id="locations-list:num-washes"
        >
            <span
                v-if="loading"
                class="loading-indicator loading-indicator__small loading"
                style="height: 27px;"
            /><span>{{ (loading && ' ') || locations.length || '0' }} wash{{
                locations.length === 1 ? '' : 'es'
            }}</span>
            <span v-if="radiusHasCenter">within {{ radius }} miles</span>
        </h3>
        <locations-list-item
            v-for="location in locations"
            :key="location.id"
            :address-line1="location.address_line_1"
            :address-line2="location.address_line_2"
            :miles-distance="
                (location.miles_distance &&
                    Math.round(parseFloat(location.miles_distance) * 10) / 10) ||
                    null
            "
            :lat="parseFloat(location.lat)"
            :lng="parseFloat(location.lng)"
            :phone="location.phone"
            :services="location.services"
            :temporarily-closed="!!location.temporarily_closed"
            :title="location.title"
            :url="location.url"
        />
    </div>
</template>

<script>
export default {
    props: {
        loading: {
            type: Boolean,
            default: false,
        },
        locations: {
            type: Array,
            required: true,
        },
        radius: {
            type: Number,
            required: true,
        },
        radiusHasCenter: {
            type: Boolean,
            required: true,
        },
    },
}
</script>
