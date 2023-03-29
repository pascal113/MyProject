<template>
    <div>
        <ul>
            <li
                v-for="service in services"
                :key="service.id"
            >
                <input
                    type="hidden"
                    :name="'services[' + service.id + '][id]'"
                    :value="getService(service.id).id"
                >
                <input
                    :id="'services[' + service.id + '][active]'"
                    type="checkbox"
                    :name="'services[' + service.id + '][active]'"
                    value="1"
                    :checked="getService(service.id).active"
                    @change="setServiceActive(service.id, $event.target.checked)"
                >
                <label :for="'services[' + service.id + '][active]'">{{ service.name }}</label>
                <i>(optional)</i>

                <div
                    v-if="getService(service.id).active"
                    class="days"
                >
                    <label>Hours of Operation</label>

                    <ul>
                        <li
                            v-for="day in 7"
                            :key="day"
                            class="day"
                        >
                            <input
                                :id="'services[' + service.id + '][days][' + day + '][active]'"
                                type="checkbox"
                                :name="'services[' + service.id + '][days][' + day + '][active]'"
                                value="1"
                                :checked="getServiceDay(service.id, day).active"
                                @change="
                                    setServiceDayActive(service.id, day, $event.target.checked)
                                "
                            >
                            <label
                                :for="'services[' + service.id + '][days][' + day + '][active]'"
                            >{{ dayNames[day] }}</label>

                            <div
                                v-if="getServiceDay(service.id, day).active"
                                class="hours-wrapper"
                            >
                                <div>
                                    <input
                                        :id="
                                            'services[' + service.id + '][days][' + day + '][is24]'
                                        "
                                        type="checkbox"
                                        :name="
                                            'services[' + service.id + '][days][' + day + '][is24]'
                                        "
                                        value="1"
                                        :checked="getServiceDay(service.id, day).is24"
                                        @change="
                                            setServiceDayIs24(
                                                service.id,
                                                day,
                                                $event.target.checked,
                                            )
                                        "
                                    >
                                    <label
                                        :for="
                                            'services[' + service.id + '][days][' + day + '][is24]'
                                        "
                                    >Open 24 Hours</label>
                                </div>

                                <div v-if="!getServiceDay(service.id, day).is24">
                                    <div>
                                        <label
                                            :for="
                                                'services[' +
                                                    service.id +
                                                    '][days][' +
                                                    day +
                                                    '][opens_at]'
                                            "
                                        >Opens at</label>
                                        <input
                                            :id="
                                                'services[' +
                                                    service.id +
                                                    '][days][' +
                                                    day +
                                                    '][opens_at]'
                                            "
                                            type="time"
                                            step="60"
                                            :name="
                                                'services[' +
                                                    service.id +
                                                    '][days][' +
                                                    day +
                                                    '][opens_at]'
                                            "
                                            :value="getServiceDay(service.id, day).opensAt"
                                            @change="
                                                setServiceDayOpensAt(
                                                    service.id,
                                                    day,
                                                    $event.target.value,
                                                )
                                            "
                                        >
                                    </div>

                                    <div>
                                        <label
                                            :for="
                                                'services[' +
                                                    service.id +
                                                    '][days][' +
                                                    day +
                                                    '][closes_at]'
                                            "
                                        >Closes at</label>
                                        <input
                                            :id="
                                                'services[' +
                                                    service.id +
                                                    '][days][' +
                                                    day +
                                                    '][closes_at]'
                                            "
                                            type="time"
                                            step="60"
                                            :name="
                                                'services[' +
                                                    service.id +
                                                    '][days][' +
                                                    day +
                                                    '][closes_at]'
                                            "
                                            :value="getServiceDay(service.id, day).closesAt"
                                            @change="
                                                setServiceDayClosesAt(
                                                    service.id,
                                                    day,
                                                    $event.target.value,
                                                )
                                            "
                                        >
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div
                    v-if="getService(service.id).active"
                    class="price-range form-group"
                >
                    <label :for="'services[' + service.id + '][price_range]'">Price range</label>
                    <i>(optional)</i>
                    <p
                        :id="'services[' + service.id + '][price_range]HelpBlock'"
                        class="form-text text-muted"
                    >
                        The relative price range of a business, commonly specified by either a
                        numerical range (for example, "$10-15") or a normalized number of currency
                        signs (for example, "$$$")
                    </p>
                    <input
                        :id="'services[' + service.id + '][price_range]'"
                        type="text"
                        class="form-control"
                        :name="'services[' + service.id + '][price_range]'"
                        :value="getService(service.id).price_range"
                    >
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            required: true,
        },
        options: {
            type: Object,
            default: () => ({}),
        },
        row: {
            type: Object,
            required: true,
        },
        services: {
            type: Array,
            default: () => [],
        },
    },

    data: () => ({
        dayNames: {
            1: 'Monday',
            2: 'Tuesday',
            3: 'Wednesday',
            4: 'Thursday',
            5: 'Friday',
            6: 'Saturday',
            7: 'Sunday',
        },
        state: { services: {} },
    }),

    beforeMount: function() {
        this.state.services = this.services.map(service => ({
            ...service,
            active: !!service.pivot,
            days: Array.from({ length: 7 }).map((v, iMinusOne) => {
                const index = iMinusOne + 1
                const day = (service.days && service.days[index]) || null

                return {
                    day: index,
                    active: (day && day.active) || false,
                    is24: !day || (day && !day.active && true) || (day && day.is24) || false,
                    opensAt: (day && day.opens_at) || null,
                    closesAt: (day && day.closes_at) || null,
                }
            }),
        }))
    },

    methods: {
        onServiceChange: function() {},

        getService: function(id) {
            return this.state.services.find(service => service.id === id)
        },

        setServiceActive: function(serviceId, value) {
            this.getService(serviceId).active = value
        },

        getServiceDay: function(serviceId, day) {
            const service = this.getService(serviceId)
            if (!service) {
                return null
            }

            return service.days.find(serviceDay => serviceDay.day === day)
        },

        setServiceDayActive: function(serviceId, day, value) {
            this.getServiceDay(serviceId, day).active = value
        },

        setServiceDayIs24: function(serviceId, day, value) {
            this.getServiceDay(serviceId, day).is24 = value
        },

        setServiceDayOpensAt: function(serviceId, day, value) {
            this.getServiceDay(serviceId, day).opensAt = value
        },

        setServiceDayClosesAt: function(serviceId, day, value) {
            this.getServiceDay(serviceId, day).closesAt = value
        },
    },
}
</script>

<style lang="scss" scoped>
ul {
    margin: 0;
    padding: 0;

    li {
        list-style-type: none;
        margin: 0 0 10px 10px;
        padding-bottom: 10px;
        border-bottom: solid 1px #ccc;
        &:last-child {
            border-bottom: none;
        }

        div.days {
            margin-left: 20px;

            > ul {
                display: flex;

                li.day {
                    flex: 1;
                    border: none;

                    .hours-wrapper {
                        display: block;
                        margin-left: 10px;
                    }
                }
            }
        }

        div.price-range {
            margin-left: 20px;
        }
    }
}
</style>
