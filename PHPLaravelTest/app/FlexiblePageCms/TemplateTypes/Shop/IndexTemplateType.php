<?php

namespace App\FlexiblePageCms\TemplateTypes\Shop;

use App\FlexiblePageCms\TemplateTypes\OverviewTemplateType;

class IndexTemplateType extends OverviewTemplateType
{
    /**
     * Allowed roles
     *
     * @return array
     */
    public static function allowedRoles(): array
    {
        return [
            'super-admin',
        ];
    }
}
