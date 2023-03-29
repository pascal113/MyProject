<?php

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;

class DummyOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
            [
                1 => [ 'qty' => 1 ], // $product_id => options
                2 => [ 'qty' => 3 ],
            ],
            [
                4 => [ 'qty' => 2 ],
            ],
        ] as $lineItems) {
            $lineItems = collect($lineItems)->map(function ($options, $id) {
                return (object)[
                    'id' => $options['id'] ?? null,
                    'product' => Product::find($id),
                    'qty' => $options['qty'],
                ];
            });
            $order = Order::create([
                'user_id' => 1,
                'discount' => 0,
                'sub_total' => $lineItems->reduce(function ($acc, $lineItem) {
                    $acc += $lineItem->product->getPriceEach($lineItem->variant ?? null, $lineItem->qty) * $lineItem->qty / 100;

                    return $acc;
                }, 0),
                'shipping_price' => $lineItems->reduce(function ($acc, $lineItem) {
                    $acc += $lineItem->product->getShippingPriceByQuantity($lineItem->qty);

                    return $acc;
                }, 0),
                'tax_rate' => Voyager::setting('sales-tax.global'),
                'tax' => $lineItems->reduce(function ($acc, $lineItem) {
                    $acc += $lineItem->product->getPriceEach($lineItem->variant ?? null, $lineItem->qty) * $lineItem->qty / 100 * Voyager::setting('sales-tax.global') / 100;

                    return $acc;
                }, 0),
                'total' => $lineItems->reduce(function ($acc, $lineItem) {
                    $acc += $lineItem->product->getPriceEach($lineItem->variant ?? null, $lineItem->qty) * $lineItem->qty / 100;
                    $acc += $lineItem->product->getPriceEach($lineItem->variant ?? null, $lineItem->qty) * $lineItem->qty / 100 * Voyager::setting('sales-tax.global') / 100;
                    $acc += $lineItem->product->getShippingPriceByQuantity($lineItem->qty);

                    return $acc;
                }, 0),
                'transaction_id' => '12345676',
                'shipping_first_name' => 'Pat',
                'shipping_last_name' => 'Itso',
                'shipping_address' => '1234 Test St.',
                'shipping_city' => 'Testville',
                'shipping_state' => 'HI',
                'shipping_zip' => '12345',
                'shipping_phone' => '123-123-1234',
            ]);
            foreach ($lineItems as $lineItem) {
                $priceEa = $lineItem->product->getPriceEach($lineItem->variant ?? null, $lineItem->qty);

                DB::table('order_product')->insert([
                    'id' => $lineItem->id,
                    'order_id' => $order->id,
                    'product_id' => $lineItem->product->id,
                    'purchase_price_ea' => $priceEa,
                    'qty' => $lineItem->qty,
                    'pre_discount_sub_total' => $priceEa * $lineItem->qty,
                    'discount' => 0,
                    'sub_total' => $priceEa * $lineItem->qty,
                    'created_at' => Carbon::now(),
                ]);
            }

            $order = $order->setStatus(Order::STATUS_PENDING);
            $order->save();
        }
    }
}
