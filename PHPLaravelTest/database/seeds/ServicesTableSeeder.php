<?php

use App\Models\Service;

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'Tunnel Wash',
            'slug' => 'tunnel-wash',
            'icon_class' => 'tunnel',
            'schema_type' => 'AutoWash',
        ]);
        Service::create([
            'name' => 'Self-Serve Wash',
            'slug' => 'self-serve-wash',
            'icon_class' => 'self',
            'schema_type' => 'AutoWash',
        ]);
        Service::create([
            'name' => 'Touchless Wash',
            'slug' => 'touchless-wash',
            'icon_class' => 'touchless',
            'schema_type' => 'AutoWash',
        ]);
        Service::create([
            'name' => 'Hungry Bear Market',
            'slug' => 'hungry-bear-market',
            'icon_class' => 'market',
            'schema_type' => 'ConvenienceStore',
        ]);
        Service::create([
            'name' => 'Gas',
            'slug' => 'gas',
            'icon_class' => 'gas',
            'schema_type' => 'GasStation',
        ]);
    }
}
