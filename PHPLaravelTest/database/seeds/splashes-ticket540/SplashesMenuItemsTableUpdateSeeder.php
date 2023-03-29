<?php

use Illuminate\Database\Seeder;

class SplashesMenuItemsTableUpdateSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('menu_items')->where('parent_id', null)->where('order', '>=', 4)->increment('order');
        \DB::table('menu_items')->insert([
            [
                'menu_id' => 1,
                'title' => 'Splashes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-whale',
                'color' => null,
                'parent_id' => null,
                'order' => 4,
                'created_at' => '2019-09-17 06:34:04',
                'updated_at' => '2019-09-17 06:34:04',
                'route' => 'voyager.splashes.index',
                'parameters' => null,
            ],
        ]);
    }
}
