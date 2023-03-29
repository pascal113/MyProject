<?php

use App\Models\Coupon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AllowNonUniqueCouponCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropUnique('coupons_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            // To prevent errors during CI deployment on dev/qa due to non-unique
            // coupon codes existing when trying to re-apply the unique()
            // constraint, delete all coupone first.
            DB::table('coupon_product')->truncate();
            Coupon::each(function ($coupon) {
                $coupon->delete();
            });

            $table->string('code')->unique()->change();
        });
    }
}
