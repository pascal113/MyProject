<?php

declare(strict_types=1);

use App\Models\ProductCategory;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder
{
    use FileCopyTrait;

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $productCategories = collect([
            'memberships',
            'paw-packs-ticket-books',
            'branded-merchandise',
        ])->reduce(function ($acc, $slug) {
            $acc[$slug] = ProductCategory::where('slug', $slug)->firstOrFail();

            return $acc;
        }, []);

        $srcImages = [
            'branded_1' => 'images/products/branded-merchandise/antenna-ball.jpg',
            'branded_2' => 'images/products/branded-merchandise/teddy-bear.jpg',
            'branded_3' => 'images/products/branded-merchandise/teddy-bear-large.jpg',
            'branded_4' => 'images/products/branded-merchandise/water-bottle.jpg',
            'branded_5' => 'images/products/branded-merchandise/bubbles.jpg',
            'branded_6' => 'images/products/branded-merchandise/air-fresheners.jpg',
            'paw_packs_1' => 'images/products/wash-packs/wash-pack-clean-single.jpg',
            'paw_packs_2' => 'images/products/wash-packs/wash-pack-best-single.jpg',
            'paw_packs_3' => 'images/products/wash-packs/wash-pack-clean-ticket-book.jpg',
            'paw_packs_4' => 'images/products/wash-packs/wash-pack-best-ticket-book.jpg',
            'paw_packs_5' => 'images/products/wash-packs/wash-pack-green.jpg',
            'paw_packs_6' => 'images/products/wash-packs/wash-pack-50.jpg',
            'paw_packs_7' => 'images/products/wash-packs/wash-pack-beary-best.jpg',
            'unlimited_1' => 'images/products/_FPO/single-ticket.png',
        ];

        $destImages = $this->copyFilesToPageStorage($srcImages);

        DB::table('products')->delete();
        DB::table('products')->insert([
            [
                'id' => 1,
                'product_category_id' => $productCategories['branded-merchandise']->id,
                'name' => 'Antenna Ball',
                'description' => "<p>Show off your Brown Bear pride with this stylish, collectable antenna ball! Doubles as a pencil topper!</p>",
                'image' => $destImages['branded_1'],
                'num_washes' => null,
            ],
            [
                'id' => 2,
                'product_category_id' => $productCategories['branded-merchandise']->id,
                'name' => 'Stuffed Brown Bear Mini',
                'description' => "<p>Smaller Soft and Squishy!<br>6\" tall. 100% polyester</p>",
                'image' => $destImages['branded_2'],
                'num_washes' => null,
            ],
            [
                'id' => 3,
                'product_category_id' => $productCategories['branded-merchandise']->id,
                'name' => 'Stuffed Brown Bear',
                'description' => "<p>Soft and Squishy! 10\" tall. 100% polyester</p>",
                'image' => $destImages['branded_3'],
                'num_washes' => null,
            ],
            [
                'id' => 4,
                'product_category_id' => $productCategories['branded-merchandise']->id,
                'name' => 'DrinkGreen® Water Bottle',
                'description' => "<p>24-ounce stainless steel water bottle<br>"
                    ."Front: DrinkGreen® logo<br>"
                    ."Back: Brown Bear Car Wash® logo</p>",
                'image' => $destImages['branded_4'],
                'num_washes' => null,
            ],
            [
                'id' => 5,
                'product_category_id' => $productCategories['branded-merchandise']->id,
                'name' => 'Bottle of Bubbles',
                'description' => "<p>1 bottle of Brown Bear Bubble Blowing solution. 4 fluid ounces Non-toxic</p>",
                'image' => $destImages['branded_5'],
                'num_washes' => null,
            ],
            [
                'id' => 6,
                'product_category_id' => $productCategories['branded-merchandise']->id,
                'name' => 'Bear Freshener 3-pack',
                'description' => "<p>Keep your car smelling Beary Fresh! 3-pack of Bear Fresheners</p>",
                'image' => $destImages['branded_6'],
                'num_washes' => null,
            ],
            [
                'id' => 7,
                'product_category_id' => $productCategories['paw-packs-ticket-books']->id,
                'name' => 'Beary Clean Single Ticket',
                'description' => "<p>1 Beary Clean car wash ticket, valid at any tunnel location</p>",
                'image' => $destImages['paw_packs_1'],
                'num_washes' => 1,
            ],
            [
                'id' => 8,
                'product_category_id' => $productCategories['paw-packs-ticket-books']->id,
                'name' => 'Beary Best Single Ticket',
                'description' => "<p>1 Beary Best car wash ticket, valid at any tunnel location</p>",
                'image' => $destImages['paw_packs_2'],
                'num_washes' => 1,
            ],
            [
                'id' => 9,
                'product_category_id' => $productCategories['paw-packs-ticket-books']->id,
                'name' => 'Beary Clean Ticket Book',
                'description' => "<p>10 Beary Clean car wash ticket, valid at any tunnel location</p>",
                'image' => $destImages['paw_packs_3'],
                'num_washes' => 10,
            ],
            [
                'id' => 10,
                'product_category_id' => $productCategories['paw-packs-ticket-books']->id,
                'name' => 'Beary Best Ticket Book',
                'description' => "<p>10 Beary Best car wash ticket, valid at any tunnel location</p>",
                'image' => $destImages['paw_packs_4'],
                'num_washes' => 10,
            ],
            [
                'id' => 11,
                'product_category_id' => $productCategories['paw-packs-ticket-books']->id,
                'name' => 'Wash Green® Paw Pack',
                'description' => "<p>3 Beary Clean, 1 Beary Best car washes, valid at any tunnel location</p>",
                'image' => $destImages['paw_packs_5'],
                'num_washes' => 4,
            ],
            [
                'id' => 12,
                'product_category_id' => $productCategories['paw-packs-ticket-books']->id,
                'name' => '50/50 Paw Pack',
                'description' => "<p>3 Beary Clean, 3 Beary Best car washes, valid at any tunnel location</p>",
                'image' => $destImages['paw_packs_6'],
                'num_washes' => 6,
            ],
            [
                'id' => 13,
                'product_category_id' => $productCategories['paw-packs-ticket-books']->id,
                'name' => 'Beary Best Paw Pack',
                'description' => "<p>5 Beary Best car washes, valid at any tunnel location</p>",
                'image' => $destImages['paw_packs_7'],
                'num_washes' => 5,
            ],
            [
                'id' => 14,
                'product_category_id' => $productCategories['memberships']->id,
                'name' => 'Beary Clean Unlimited Wash Club',
                'description' => "<ul>
                     <li>Soft cloth exterior wash</li>
                      <li>Spot-free rinse</li>
                      <li>Air dry</li>
                    </ul>",
                'image' => $destImages['unlimited_1'],
                'num_washes' => null,
            ],
            [
                'id' => 15,
                'product_category_id' => $productCategories['memberships']->id,
                'name' => 'Beary Bright Unlimited Wash Club',
                'description' => "<ul>
                      <li>Tri-Color Polish</li>
                      <li>Chassis Armor</li>
                      <li>Undercarriage Wash Rust Inhibitor</li>
                      <li>Soft cloth exterior wash</li>
                      <li>Spot-free rinse</li>
                      <li>Air dry</li>
                    </ul>",
                'image' => $destImages['unlimited_1'],
                'num_washes' => null,
            ],
            [
                'id' => 16,
                'product_category_id' => $productCategories['memberships']->id,
                'name' => 'Beary Best Unlimited Wash Club',
                'description' => "<ul>
                      <li>Clear Coat Protectant</li>
                      <li>Tri-Color Polish</li>
                      <li>Chassis Armor</li>
                      <li>Undercarriage Wash Rust Inhibitor</li>
                      <li>Soft cloth exterior wash</li>
                      <li>Spot-free rinse</li>
                      <li>Air dry</li>
                    </ul>",
                'image' => $destImages['unlimited_1'],
                'num_washes' => null,
            ],
            [
                'id' => 17,
                'product_category_id' => $productCategories['memberships']->id,
                'name' => 'For Hire Beary Clean Unlimited Wash Club',
                'description' => "<ul>
                     <li>Soft cloth exterior wash</li>
                      <li>Spot-free rinse</li>
                      <li>Air dry</li>
                    </ul>",
                'image' => $destImages['unlimited_1'],
                'num_washes' => null,
            ],
            [
                'id' => 18,
                'product_category_id' => $productCategories['memberships']->id,
                'name' => 'For Hire Beary Bright Unlimited Wash Club',
                'description' => "<ul>
                      <li>Tri-Color Polish</li>
                      <li>Chassis Armor</li>
                      <li>Undercarriage Wash Rust Inhibitor</li>
                      <li>Soft cloth exterior wash</li>
                      <li>Spot-free rinse</li>
                      <li>Air dry</li>
                    </ul>",
                'image' => $destImages['unlimited_1'],
                'num_washes' => null,
            ],
            [
                'id' => 19,
                'product_category_id' => $productCategories['memberships']->id,
                'name' => 'For Hire Beary Best Unlimited Wash Club',
                'description' => "<ul>
                      <li>Clear Coat Protectant</li>
                      <li>Tri-Color Polish</li>
                      <li>Chassis Armor</li>
                      <li>Undercarriage Wash Rust Inhibitor</li>
                      <li>Soft cloth exterior wash</li>
                      <li>Spot-free rinse</li>
                      <li>Air dry</li>
                    </ul>",
                'image' => $destImages['unlimited_1'],
                'num_washes' => null,
            ],
        ]);

        foreach ([
            [
                'product_id' => 1,
                'price' => 0.99,
            ],
            [
                'product_id' => 2,
                'price' => 7.99,
            ],
            [
                'product_id' => 3,
                'price' => 12.99,
            ],
            [
                'product_id' => 4,
                'price' => 14.99,
            ],
            [
                'product_id' => 5,
                'price' => 1.99,
            ],
            [
                'product_id' => 6,
                'price' => 0.99,
            ],
            [
                'product_id' => 7,
                'price' => 8.17,
            ],
            [
                'product_id' => 8,
                'price' => 13.62,
            ],
            [
                'product_id' => 9,
                'qty_from' => 1,
                'qty_to' => 9,
                'price' => 89.82,
            ],
            [
                'product_id' => 9,
                'qty_from' => 10,
                'price' => 84.83,
            ],
            [
                'product_id' => 10,
                'qty_from' => 1,
                'qty_to' => 9,
                'price' => 114.30,
            ],
            [
                'product_id' => 10,
                'qty_from' => 10,
                'price' => 107.95,
            ],
            [
                'product_id' => 11,
                'price' => 35.43,
            ],
            [
                'product_id' => 12,
                'price' => 59.04,
            ],
            [
                'product_id' => 13,
                'price' => 63.58,
            ],
        ] as $pivot) {
            ProductPrice::create([
                'product_id' => $pivot['product_id'],
                'qty_from' => $pivot['qty_from'] ?? 1,
                'qty_to' => $pivot['qty_to'] ?? null,
                'price_each' => $pivot['price'],
            ]);
        }

        $products = \App\Models\Product::all();
        $shippingPrices = [];
        foreach ($products as $product) {
            $shippingPrices[] = [
                'qty_from' => 1,
                'price_each' => 100,
                'product_id' => $product->id,
            ];
        }

        DB::table('product_shipping_prices')->delete();
        DB::table('product_shipping_prices')->insert($shippingPrices);
    }
}
