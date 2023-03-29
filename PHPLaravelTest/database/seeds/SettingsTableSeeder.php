<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    use FileCopyTrait;

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $srcImages = [
            'loader' => 'images/bbc-admin-loader.png',
            'logo' => 'images/bbc-admin-logo.png',
            'bg' => 'images/bbc-admin-bg.jpg',
        ];

        $destImages = $this->copyFilesToStorage($srcImages);

        \DB::table('settings')->delete();

        \DB::table('settings')->insert([
            0 =>
            [
                'id' => 1,
                'key' => 'site.title',
                'display_name' => 'Site Title',
                'value' => 'brownbear.com',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Site',
            ],
            1 =>
            [
                'id' => 2,
                'key' => 'site.description',
                'display_name' => 'Site Description',
                'value' => '',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Site',
            ],
            2 =>
            [
                'id' => 3,
                'key' => 'site.logo',
                'display_name' => 'Site Logo',
                'value' => $destImages['logo'],
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Site',
            ],
            3 =>
            [
                'id' => 4,
                'key' => 'site.google_analytics_tracking_id',
                'display_name' => 'Google Analytics Tracking ID',
                'value' => '',
                'details' => '',
                'type' => 'text',
                'order' => 4,
                'group' => 'Site',
            ],
            4 =>
            [
                'id' => 5,
                'key' => 'admin.bg_image',
                'display_name' => 'Admin Background Image',
                'value' => $destImages['bg'],
                'details' => '',
                'type' => 'image',
                'order' => 5,
                'group' => 'Admin',
            ],
            5 =>
            [
                'id' => 6,
                'key' => 'admin.title',
                'display_name' => 'Admin Title',
                'value' => 'brownbear.com',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ],
            6 =>
            [
                'id' => 7,
                'key' => 'admin.description',
                'display_name' => 'Admin Description',
                'value' => 'Website administration',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Admin',
            ],
            7 =>
            [
                'id' => 8,
                'key' => 'admin.loader',
                'display_name' => 'Admin Loader',
                'value' => $destImages['loader'],
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Admin',
            ],
            8 =>
            [
                'id' => 9,
                'key' => 'admin.icon_image',
                'display_name' => 'Admin Icon Image',
                'value' => $destImages['logo'],
                'details' => '',
                'type' => 'image',
                'order' => 4,
                'group' => 'Admin',
            ],
            9 =>
            [
                'id' => 10,
                'key' => 'admin.google_analytics_client_id',
                'display_name' => 'Google Analytics Client ID (used for admin dashboard)',
                'value' => '',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ],
            [
                'id' => 11,
                'key' => 'sales-tax.global',
                'display_name' => 'Global Sales Tax',
                'value' => '10.1',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Sales Tax',
            ],
        ]);
    }
}
