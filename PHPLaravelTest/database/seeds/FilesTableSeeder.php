<?php

use App\Models\FileCategory;
use App\Services\FileService;
use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $categories = collect([
            'training-documents',
            'training-videos',
            'c-store-tasks-procedures',
        ])->reduce(function ($acc, $slug) {
            $acc[$slug] = FileCategory::where('slug', $slug)->firstOrFail();

            return $acc;
        }, []);

        $files = collect([
            [
                'file_category_id' => $categories['training-documents']->id,
                'name' => 'Defensive Driving Training Form',
                'src_url' => 'https://brown-bear-redesign-production.s3-us-west-2.amazonaws.com/intranet_files/106/original/Driver_Safety_-_Defensive_Driving_Training.pdf?1530560533',
                'order' => 1,
                'created_at' => '2019-12-31 10:15:42',
            ],
            [
                'file_category_id' => $categories['training-videos']->id,
                'name' => "It's Worth Your Life",
                'src_url' => 'https://www.youtube.com/watch?v=aKttrVw6HKo',
                'is_external_url' => true,
                'order' => 1,
                'created_at' => '2019-12-31 10:15:42',
            ],
            [
                'file_category_id' => $categories['training-videos']->id,
                'name' => 'Workplace Hazards',
                'src_url' => 'https://i.imgur.com/3rR96WE.mp4',
                'order' => 2,
                'created_at' => '2019-12-31 10:15:42',
            ],
            [
                'file_category_id' => $categories['c-store-tasks-procedures']->id,
                'name' => 'HBM Audit Procedures 9-2015',
                'src_url' => 'https://brown-bear-redesign-production.s3-us-west-2.amazonaws.com/intranet_files/29/original/HBM_Audit_Procedures_9-2015.docx?1481572001',
                'order' => 1,
                'created_at' => '2019-12-31 10:15:42',
            ],
        ])->reduce(function ($acc, $file) {
            if ($file['is_external_url'] ?? false) {
                $file['path'] = $file['src_url'];
                $file['mime_type'] = 'external_url';
            } else {
                // Move to S3
                list($contents, $fileName, $mimeType) = FileService::fetchExternalFileFromUrl($file['src_url']);

                if (!$contents) {
                    return $acc;
                }

                FileService::delete($fileName);
                $fileName = FileService::put($contents, $fileName);

                if (!$fileName) {
                    return $acc;
                }

                $file['path'] = $fileName;
                $file['mime_type'] = $mimeType;
            }

            unset($file['src_url']);
            unset($file['is_external_url']);

            $acc[] = $file;

            return $acc;
        }, []);

        \DB::table('files')->delete();
        \DB::table('files')->insert($files);
    }
}
