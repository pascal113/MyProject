<?php

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSlugsUpdate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::where('slug', 'tunnel-wash')
            ->update([ 'slug' => 'tunnel-car-wash' ]);

        Service::where('slug', 'self-serve-wash')
            ->update([ 'slug' => 'self-serve-car-wash' ]);

        Service::where('slug', 'touchless-wash')
            ->update([ 'slug' => 'touchless-car-wash' ]);
    }
}
