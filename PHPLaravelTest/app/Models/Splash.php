<?php


namespace App\Models;

use App\Model;

class Splash extends Model
{
    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'image',
        'starts_at',
        'ends_at',
        'link_url',
        'is_enabled',
    ];
}
