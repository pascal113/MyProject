<?php

namespace App\Voyager\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Menu extends \TCG\Voyager\Models\Menu
{
    protected static function processItems($items)
    {
        // Eagerload Translations
        if (config('voyager.multilingual.enabled')) {
            $items->load('translations');
        }

        $items = $items->transform(function ($item) {
            // Translate title
            $item->title = $item->getTranslatedAttribute('title');
            // Resolve URL/Route
            $item->href = $item->link(true);

            if ($item->href === url()->current() && $item->href !== '') {
                // The current URL is exactly the URL of the menu-item
                $item->active = true;
            } elseif (Str::startsWith(url()->current(), Str::finish($item->href, '/'))) {
                // The current URL is "below" the menu-item URL. For example "admin/posts/1/edit" => "admin/posts"
                $item->active = true;
            }
            if (($item->href === url('') || $item->href === route('voyager.dashboard')) && $item->children->count() > 0) {
                // Exclude sub-menus
                $item->active = false;
            } elseif ($item->href === route('voyager.dashboard') && url()->current() !== route('voyager.dashboard')) {
                // Exclude dashboard
                $item->active = false;
            }

            if ($item->children->count() > 0) {
                $item->setRelation('children', static::processItems($item->children));

                if (!$item->children->where('active', true)->isEmpty()) {
                    $item->active = true;
                }
            }

            return $item;
        });

        // Filter items by permission
        $items = $items->filter(function ($item) {
            return !$item->children->isEmpty() || Auth::user()->can('browse', $item);
        })->filter(function ($item) {
            // Filter out empty menu-items
            if ($item->icon_class && $item->url === '' && $item->route === '' && $item->children->count() === 0) {
                return false;
            }

            return true;
        });

        return $items->values();
    }
}
