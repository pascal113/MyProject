<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddWashConnectIdToLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('wash_connect_id')->nullable()->after('site_number');
        });

        DB::table('locations')
            ->where('site_number', '1002')
            ->update([ 'wash_connect_id' => '5015001695' ]);

        DB::table('locations')
            ->where('site_number', '1043')
            ->update([ 'wash_connect_id' => '5015003282' ]);

        DB::table('locations')
            ->where('site_number', '1000')
            ->update([ 'wash_connect_id' => '5015008075' ]);

        DB::table('locations')
            ->where('site_number', '1022')
            ->update([ 'wash_connect_id' => '5015008868' ]);

        DB::table('locations')
            ->where('site_number', '1032')
            ->update([ 'wash_connect_id' => '5015009240' ]);

        DB::table('locations')
            ->where('site_number', '1051')
            ->update([ 'wash_connect_id' => '5015009533' ]);

        DB::table('locations')
            ->where('site_number', '1017')
            ->update([ 'wash_connect_id' => '5015007690' ]);

        DB::table('locations')
            ->where('site_number', '1010')
            ->update([ 'wash_connect_id' => '5015006940' ]);

        DB::table('locations')
            ->where('site_number', '1016/1048')
            ->update([ 'wash_connect_id' => '5015007028' ]);

        DB::table('locations')
            ->where('site_number', '1005')
            ->update([ 'wash_connect_id' => '5015003386' ]);

        DB::table('locations')
            ->where('site_number', '1031')
            ->update([ 'wash_connect_id' => '5015007558' ]);

        DB::table('locations')
            ->where('site_number', '1029/1030')
            ->update([ 'wash_connect_id' => '5015009383' ]);

        DB::table('locations')
            ->where('site_number', '1004')
            ->update([ 'wash_connect_id' => '5015006435' ]);

        DB::table('locations')
            ->where('site_number', '1009')
            ->update([ 'wash_connect_id' => '5015002136' ]);

        DB::table('locations')
            ->where('site_number', '901')
            ->update([ 'wash_connect_id' => '5015001098' ]);

        DB::table('locations')
            ->where('site_number', '1011/1023')
            ->update([ 'wash_connect_id' => '5015009282' ]);

        DB::table('locations')
            ->where('site_number', '1014')
            ->update([ 'wash_connect_id' => '5015004263' ]);

        DB::table('locations')
            ->where('site_number', '1038')
            ->update([ 'wash_connect_id' => '5015008405' ]);

        DB::table('locations')
            ->where('site_number', '1015')
            ->update([ 'wash_connect_id' => '5015005015' ]);

        DB::table('locations')
            ->where('site_number', '1008')
            ->update([ 'wash_connect_id' => '5015005796' ]);

        DB::table('locations')
            ->where('site_number', '1040/1041')
            ->update([ 'wash_connect_id' => '5015001037' ]);

        DB::table('locations')
            ->where('site_number', '0101')
            ->update([ 'wash_connect_id' => '5015004683' ]);

        DB::table('locations')
            ->where('site_number', '0105/1056')
            ->update([ 'wash_connect_id' => '5015002492' ]);

        DB::table('locations')
            ->where('site_number', '1034')
            ->update([ 'wash_connect_id' => '5015002469' ]);

        DB::table('locations')
            ->where('site_number', '1033')
            ->update([ 'wash_connect_id' => '5015007870' ]);

        DB::table('locations')
            ->where('site_number', '1057')
            ->update([ 'wash_connect_id' => '5015007371' ]);

        DB::table('locations')
            ->where('site_number', '1058')
            ->update([ 'wash_connect_id' => '5015009704' ]);

        DB::table('locations')
            ->where('site_number', '1060')
            ->update([ 'wash_connect_id' => '5015002715' ]);

        DB::table('locations')
            ->where('site_number', '1054')
            ->update([ 'wash_connect_id' => '5015002944' ]);

        DB::table('locations')
            ->where('site_number', '1061')
            ->update([ 'wash_connect_id' => '5015007753' ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('wash_connect_id');
        });
    }
}
