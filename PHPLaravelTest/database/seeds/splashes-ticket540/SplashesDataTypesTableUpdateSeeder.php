<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class SplashesDataTypesTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('data_types')->insert([
            [
                'name' => 'splashes',
                'slug' => 'splashes',
                'display_name_singular' => 'Splash',
                'display_name_plural' => 'Splashes',
                'icon' => 'voyager-whale',
                'model_name' => \App\Models\Splash::class,
                'policy_name' => null,
                'controller' => \App\Http\Controllers\Admin\SplashController::class,
                'description' => null,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":"starts_at","order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2019-11-17 18:55:12',
                'updated_at' => '2019-11-17 18:55:12',
            ],
        ]);
    }
}
