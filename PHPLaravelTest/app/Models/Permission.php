<?php

namespace App\Models;

use Illuminate\Support\Str;

class Permission extends \TCG\Voyager\Models\Permission
{
    /**
     * display_name
     */
    public function getDisplayNameAttribute(): string
    {
        return str_replace('_', ' ', Str::title($this->key));
    }
}
