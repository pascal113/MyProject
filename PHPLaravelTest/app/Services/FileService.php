<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Return full AWS S3 path to a file
     */
    private static function path(string $fileName = ''): string
    {
        return config('filesystems.disks.s3.files_dir').'/'.$fileName;
    }

    /**
     * Return collection of all AWS S3 Company Files
     */
    public static function all(): Collection
    {
        return collect(Storage::disk('s3')->files(self::path()));
    }

    /**
     * Upload a file to AWS S3
     */
    public static function put(string $contents, ?string $fileName = null): ?string
    {
        if (!$fileName) {
            $fileName = \Carbon\Carbon::now()->timestamp;
        }

        // Ensure unique filename
        while (self::exists($fileName)) {
            $regexp = '/(.+?)( \(([0-9]+)\))?(\.[^.]*$|$)/';
            $fileName = preg_replace_callback(
                $regexp,
                function ($matches) {
                    $nextIndex = (((int)$matches[3]) ?? 0) + 1;

                    return $matches[1].' ('.$nextIndex.')'.$matches[4];
                },
                $fileName
            );
        }

        if (Storage::disk('s3')->put(self::path($fileName), $contents)) {
            return $fileName;
        }

        return null;
    }

    /**
     * Return bool whether the given file exists in AWS S3
     */
    public static function exists(string $fileName): bool
    {
        return Storage::disk('s3')->exists(self::path($fileName));
    }

    /**
     * Return temporary URL for private file stored in AWS S3
     */
    public static function getTemporaryUrl(string $fileName): ?string
    {
        if (!self::exists($fileName)) {
            return null;
        }
        $expirationMinutes = config('filesystems.disks.s3.temporary_url_expiration_minutes');
        return Storage::disk('s3')
            ->temporaryUrl(self::path($fileName), \Carbon\Carbon::now()->addMinutes($expirationMinutes));
    }

    /**
     * Return the contents of a file from AWS S3
     */
    public static function get(string $fileName): ?string
    {
        if (!self::exists($fileName)) {
            return null;
        }

        return Storage::disk('s3')->get(self::path($fileName));
    }

    /**
     * Delete a file from AWS S3
     */
    public static function delete(string $fileName): ?bool
    {
        if (!self::exists($fileName)) {
            return null;
        }

        return Storage::disk('s3')->delete(self::path($fileName));
    }

    /**
     * Fetch the contents, fileName, and mimeType of an external file given its URL.
     */
    public static function fetchExternalFileFromUrl(string $url): array
    {
        $fileName = preg_replace('/^.*\/([^\/]*)$/', '$1', $url);

        $ret = [
            null,
            $fileName,
            null,
        ];

        try {
            $contents = file_get_contents($url);
        } catch (\Exception $e) {
            return $ret;
        }

        $ret[0] = $contents;

        $regexp = '/^.*content-type\s*:\s*([^;]*).*$/i';
        $header = collect($http_response_header)->first(function ($header) use ($regexp) {
            return preg_match($regexp, $header);
        });
        if ($header) {
            $ret[2] = preg_replace($regexp, '$1', $header);
        }

        return $ret;
    }
}
