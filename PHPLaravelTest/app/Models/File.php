<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use App\Services\FileService;
use Illuminate\Support\Collection;

class File extends Model
{
    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'file_category_id',
        'name',
        'path',
        'mime_type',
        'thumbnail',
        'order',
    ];

    /**
     * Get File category
     */
    public function category()
    {
        return $this->belongsTo(FileCategory::class, 'file_category_id', 'id');
    }

    /**
     * Get all files from all public Categories
     */
    public static function allPublic(): Collection
    {
        $publicCategories = FileCategory::with('files')->where('is_public', true)->get();
        $publicFiles = $publicCategories->reduce(function ($acc, $category) {
            $category->files->map(function ($file) use ($acc) {
                $acc->push($file);
            });

            return $acc;
        }, collect([]));

        return $publicFiles;
    }

    /**
     * Return url
     */
    public function getUrlAttribute(): string
    {
        return route('files.file', [$this->id]);
    }

    /**
     * Return temporary url
     */
    public function getTemporaryUrlAttribute(): ?string
    {
        return FileService::getTemporaryUrl($this->path);
    }

    /**
     * Return temporary thumbnail url
     */
    public function getTemporaryThumbnailUrlAttribute(): ?string
    {
        return FileService::getTemporaryUrl($this->thumbnail);
    }

    /**
     * Return file asset from S3
     */
    public function getFileAttribute(): ?string
    {
        return FileService::get($this->path);
    }

    /**
     * Returns file location path or temporary url based on type
     */
    public function getFileLocation()
    {
        if ($this->mime_type === 'external_url') {
            $redirectLocation = $this->path;
        } else {
            $redirectLocation = $this->temporaryUrl;
        }

        return $redirectLocation;
    }
}
