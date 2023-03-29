<?php

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->mediumInteger('product_id')->unsigned();
            $table->string('wash_connect_id')->nullable();
            $table->string('name');
            $table->bigInteger('price')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });

        $recurringProducts = Product::where('price_monthly', '>', 0)
            ->orWhere('price_annual', '>', 0)
            ->get();
        foreach ($recurringProducts as $product) {
            if ($product->price_monthly) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => 'Monthly',
                    'price' => $product->price_monthly,
                    'is_recurring' => true,
                ]);
            }
            if ($product->price_annual) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => 'Yearly',
                    'price' => $product->price_annual,
                    'is_recurring' => true,
                ]);
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => 'One Year',
                    'price' => $product->price_annual,
                ]);
            }

            DB::table('product_shipping_prices')->where('product_id', $product->id)->update([ 'price_each' => 0 ]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_monthly');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_annual');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->mediumInteger('product_variant_id')->unsigned()->nullable()->after('product_id');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->string('size')->nullable()->after('product_variant_id');
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('product_variant_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('price_monthly')->nullable()->after('num_washes');
            $table->bigInteger('price_annual')->nullable()->after('price_monthly');
        });

        foreach (ProductVariant::all() as $variant) {
            if ($variant->name === 'Monthly' || $variant->name === 'Yearly') {
                $product = Product::where('id', $variant->product_id)->first();
            }

            if (!$product) {
                continue;
            }

            if ($variant->name === 'Monthly') {
                $product->price_monthly = $variant->price;
            }
            if ($variant->name === 'Yearly') {
                $product->price_annual = $variant->price;
            }
            $product->save();
        }

        Schema::dropIfExists('product_variants');
    }
}
