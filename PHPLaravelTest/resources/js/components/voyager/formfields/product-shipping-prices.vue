<template>
    <div class="row">
        <div id="product-shipping-price-items col-md-6">
            <div
                v-for="(item, index) in items"
                :key="index"
                class="line-items col-md-12"
            >
                <div class="form-group col-md-3">
                    <label class="control-label">Quantity From</label>
                    <input
                        v-model="item.qty_from"
                        type="number"
                        class="form-control"
                        :min="(items[index - 1] && parseInt(items[index - 1].qty_from) + 1) || 1"
                        :max="(items[index + 1] && parseInt(items[index + 1].qty_from) - 1) || null"
                        :name="'product_shipping_prices[' + index + '][qty_from]'"
                        @change="qtyFromChanged(index, $event.target.value)"
                    >
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Quantity To</label>
                    <input
                        v-if="item.qty_to"
                        v-model="item.qty_to"
                        type="hidden"
                        :name="'product_shipping_prices[' + index + '][qty_to]'"
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
                                :name="'product_shipping_prices[' + index + '][price_each]'"
                                step="0.01"
                                required
                                min="0"
                                @change="priceEachChanged(index, $event.target.value)"
                            >
                        </div>
                    </div>
                </div>
                <div
                    v-if="index === items.length - 1 && items.length > 1"
                    class="button-remove-item-container col-md-3"
                >
                    <button
                        type="button"
                        class="btn btn-sm btn-danger"
                        @click="removeItem(index)"
                    >
                        Remove
                    </button>
                </div>
            </div>
            <div class="col-md-12">
                <button
                    type="button"
                    class="btn btn-success btn-lg "
                    @click="addItem"
                >
                    Add a Shipping Price Range
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        prices: {
            type: Array,
            default: () => [],
        },
    },

    data: () => ({ items: [] }),

    mounted: function() {
        if (this.prices && this.prices.length > 0) {
            this.items = this.prices
        }
        else {
            this.addItem()
        }

        this.formatPrices()
    },

    methods: {
        addItem: function() {
            let item = {
                qty_from: 1,
                qty_to: null,
                price_each: null,
            }

            if (this.items.length > 0) {
                const prevItemQuantityTo = this.getPreviousItemToQuantity()

                if (!prevItemQuantityTo) {
                    this.setPreviousItemToQuantity()
                }

                this.items[this.items.length - 1].qty_to = this.getPreviousItemToQuantity()
                item.qty_from = this.getPreviousItemToQuantity() + 1
            }

            this.items.push(item)
        },

        removeItem: function(index) {
            this.items.splice(index, 1)
            this.items[index - 1].qty_to = null
        },

        getPreviousItemToQuantity: function() {
            return parseInt(this.items[this.items.length - 1].qty_to, 10)
        },

        setPreviousItemToQuantity: function() {
            this.items[this.items.length - 1].qty_to =
                parseInt(this.items[this.items.length - 1].qty_from, 10) + 1
        },

        qtyFromChanged: function(index, value) {
            if (index) {
                this.items[index - 1].qty_to = value - 1
            }
        },

        priceEachChanged: function() {
            this.formatPrices()
        },

        formatPrices: function() {
            this.items = this.items.map(item => ({
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
