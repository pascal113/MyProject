<?php

declare(strict_types=1);

use Illuminate\Database\Seeder as BaseSeeder;

class Seeder extends BaseSeeder
{
    /**
     * Err out if data type is not found
     *
     * @param string $name
     * @return void
     */
    protected static function dataTypeNotFound(string $name)
    {
        dd('⚠️  "'.$name.'" DataType Not Found. Halting... ☠️');
    }
}
