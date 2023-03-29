<template>
    <div>
        <div class="form-group col-md-12">
            <label
                class="control-label"
                for="product_category_id"
            >
                Category
            </label>

            <select
                id="product_category_id"
                v-model="state.productCategoryId"
                class="form-control"
                name="product_category_id"
                required
            >
                <optgroup>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </optgroup>
            </select>
        </div>

        <div
            v-for="input in inputs"
            :key="input.name"
            class="form-group col-md-12"
        >
            <label
                class="control-label"
                :for="input.name"
            >
                {{ input.label }}
            </label>
            <div :class="(input.type === 'price' && 'input-group') || ''">
                <span
                    v-if="input.type === 'price'"
                    class="input-group-addon"
                >$</span>
                <input
                    v-if="input.type !== 'prices'"
                    :id="input.name"
                    v-model="state[input.model]"
                    :name="input.name"
                    :placeholder="input.label"
                    :required="input.required || false"
                    :step="input.step || 'any'"
                    :type="(input.type === 'price' && 'number') || input.type || 'text'"
                    class="form-control"
                >
                <div v-if="input.type === 'prices'">
                    <div
                        v-for="(item, index) in state.prices"
                        :key="index"
                        class="line-items col-md-12"
                    >
                        <div class="form-group col-md-3">
                            <label class="control-label">Quantity From</label>
                            <input
                                v-model="item.qty_from"
                                type="number"
                                class="form-control"
                                :min="
                                    (state.prices[index - 1] &&
                                        state.prices[index - 1].qty_from &&
                                        parseInt(state.prices[index - 1].qty_from) + 1) ||
                                        1
                                "
                                :max="
                                    (state.prices[index + 1] &&
                                        state.prices[index + 1].qty_from &&
                                        parseInt(state.prices[index + 1].qty_from) - 1) ||
                                        null
                                "
                                :name="input.name + '[' + index + '][qty_from]'"
                                @change="onQtyFromChanged(index, $event.target.value)"
                            >
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">Quantity To</label>
                            <input
                                v-if="item.qty_to"
                                v-model="item.qty_to"
                                type="hidden"
                                :name="input.name + '[' + index + '][qty_to]'"
                            >
                            <p class="qty_to">
                                {{ item.qty_to || '&infin;' }}
                            </p>
                        </div>
                        <div class="form-group col-md-3 price_each">
                            <label class="control-label">Price Each</label>
                            <div>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input
                                        v-model="item.price_each"
                                        type="number"
                                        class="form-control"
                                        :name="input.name + '[' + index + '][price_each]'"
                                        step="0.01"
                                        required
                                        min="0"
                                        @change="onPriceEachChanged(index, $event.target.value)"
                                    >
                                </div>
                            </div>
                        </div>
                        <div
                            v-if="index === state.prices.length - 1 && state.prices.length > 1"
                            class="button-remove-item-container col-md-3"
                        >
                            <button
                                type="button"
                                class="btn btn-sm btn-danger"
                                @click="removePrice(index)"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                    <div>
                        <button
                            type="button"
                            class="btn btn-success btn-lg "
                            @click="addPrice"
                        >
                            Add a Price Range
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            required: true,
        },
        old: {
            type: Object,
            default: () => ({}),
        },
        options: {
            type: Object,
            default: () => ({}),
        },
        row: {
            type: Object,
            required: true,
        },
        categories: {
            type: Array,
            default: () => [],
        },
        labels: {
            type: Object,
            default: () => ({}),
        },
    },

    data: () => ({
        state: {
            productCategoryId: null,
            price: null,
            prices: [],
            numWashes: null,
            priceMonthly: null,
            priceYearly: null,
        },
    }),

    computed: {
        inputs: function() {
            const inputs = []
            if (this.selectedCategory) {
                if (
                    this.selectedCategory.slug === 'branded-merchandise' ||
                    this.selectedCategory.slug === 'wash-cards-ticket-books'
                ) {
                    if (this.selectedCategory.slug === 'wash-cards-ticket-books') {
                        inputs.push({
                            label: this.labels.numWashes,
                            model: 'numWashes',
                            name: 'num_washes',
                            required: true,
                            type: 'number',
                        })
                    }
                    inputs.push({
                        label: this.labels.price,
                        name: 'prices',
                        required: true,
                        type: 'prices',
                    })
                }
                else if (this.selectedCategory.slug === 'memberships') {
                    inputs.push({
                        label: this.labels.priceMonthly,
                        model: 'priceMonthly',
                        name: 'price_monthly',
                        required: false,
                        type: 'price',
                    })
                    inputs.push({
                        label: this.labels.priceYearly,
                        model: 'priceYearly',
                        name: 'price_yearly',
                        required: false,
                        type: 'price',
                    })
                }
            }

            return inputs
        },
        selectedCategory: function() {
            return this.categories.find(
                category => category.id === parseInt(this.state.productCategoryId),
            )
        },
    },

    mounted: function() {
        this.state = {
            ...this.state,
            ...this.old,
        }

        if (!(this.state.prices && this.state.prices.length > 0)) {
            this.addPrice()
        }

        this.formatPrices()
    },

    methods: {
        addPrice: function() {
            let price = {
                qty_from: 1,
                qty_to: null,
                price_each: null,
            }

            if (this.state.prices.length > 0) {
                const prevPriceQtyTo = this.getLastPriceQtyTo()

                if (!prevPriceQtyTo) {
                    this.setLastPriceQtyTo()
                }

                this.state.prices[this.state.prices.length - 1].qty_to = this.getLastPriceQtyTo()
                price.qty_from = this.getLastPriceQtyTo() + 1
            }

            this.state.prices.push(price)
        },

        removePrice: function(index) {
            this.state.prices.splice(index, 1)
            this.state.prices[index - 1].qty_to = null
        },

        getLastPriceQtyTo: function() {
            return (
                (this.state.prices[this.state.prices.length - 1].qty_to &&
                    parseInt(this.state.prices[this.state.prices.length - 1].qty_to, 10)) ||
                ''
            )
        },

        setLastPriceQtyTo: function() {
            const newQtyTo =
                (this.state.prices[this.state.prices.length - 1].qty_from &&
                    parseInt(this.state.prices[this.state.prices.length - 1].qty_from, 10) + 1) ||
                ''

            this.state.prices[this.state.prices.length - 1].qty_to = newQtyTo
        },

        onQtyFromChanged: function(index, value) {
            if (index) {
                this.state.prices[index - 1].qty_to = value - 1
            }
        },

        onPriceEachChanged: function() {
            this.formatPrices()
        },

        formatPrices: function() {
            this.state.prices = this.state.prices.map(item => ({
                ...item,
                price_each: parseFloat(item.price_each).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }),
            }))
        },
    },
}
</script>

<style lang="scss" scoped>
.button-remove-item-container {
    vertical-align: middle;
    line-height: 91px;
}
.qty_to {
    padding-left: 10px;
    font-size: 21px;
}
.price_each {
    span {
        width: 20px;
        overflow: visible;
        text-align: right;
    }
    input {
        display: inline-block;
        width: calc(100% - 20px);
    }
}
</style>
