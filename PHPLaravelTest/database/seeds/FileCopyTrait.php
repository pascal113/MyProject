<?php


trait FileCopyTrait
{
    private $destFolder = [
        'filesystem' => 'app/public/',
        'url' => '/',
    ];
    private $pagesDestFolder = [
        'filesystem' => 'app/public/',
        'url' => '/storage/',
    ];

    public function copyFilesToStorage(array $sourceFiles): array
    {
        return $this->copyFiles($this->destFolder, $sourceFiles);
    }

    public function copyFilesToPageStorage(array $sourceFiles): array
    {
        return $this->copyFiles($this->pagesDestFolder, $sourceFiles);
    }

    public function copyFiles(array $destFolder, array $sourceFiles): array
    {
        if (!File::exists(storage_path($destFolder['filesystem']))) {
            File::makeDirectory(storage_path($destFolder['filesystem']));
        }

        $destImages = [];
        foreach ($sourceFiles as $key => $srcImage) {
            $explodedPath = explode('/', $srcImage);
            $foldersOnly = array_slice($explodedPath, 0, sizeof($explodedPath) - 1);
            $destImageFolder = $destFolder['filesystem'].implode('/', $foldersOnly);
            if (!File::exists(storage_path($destImageFolder))) {
                File::makeDirectory(storage_path($destImageFolder), 0755, true);
            }
            $seedPath = database_path('seeds/'.$srcImage);
            $storagePath = $destImageFolder;

            if (File::exists($storagePath)) {
                File::delete($storagePath);
            }

            File::copy($seedPath, storage_path($storagePath.'/'.$explodedPath[sizeof($explodedPath) - 1]));

            $destImages[$key] = $destFolder['url'].$srcImage;
        }

        return $destImages;
    }
}
