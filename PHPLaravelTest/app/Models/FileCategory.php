<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Collection;

class FileCategory extends Model
{
    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'is_public',
        'order',
    ];

    /**
     * Files within this category
     */
    public function files()
    {
        return $this->hasMany(File::class, 'file_category_id', 'id')->orderBy('order');
    }

    /**
     * Return all non-public categories
     */
    public static function allNotEmptyAndNotPublic(): Collection
    {
        return self::allNotEmpty([ [ 'is_public', false ] ]);
    }

    /**
     * Return all public categories
     */
    public static function allNotEmptyAndPublic(): Collection
    {
        return self::allNotEmpty([ [ 'is_public', true ] ]);
    }

    /**
     * Return all categories that have files within them
     */
    public static function allNotEmpty(array $where = null): Collection
    {
        $categories = self::where($where)->orderBy('order')->get()->reduce(function ($acc, $category) {
            if ($category->files->count()) {
                $acc->push($category);
            }

            return $acc;
        }, new Collection());

        return $categories;
    }
}
