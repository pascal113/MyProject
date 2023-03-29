<?php

namespace App\FlexiblePageCms\TemplateTypes;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\TemplateType;

class OverviewTemplateType extends TemplateType
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
            'admin',
            'staff',
        ];
    }

    /**
     * Returns CMS Fields
     *
     * @return array
     */
    protected static function formfields(): array
    {
        return [
            'content' => [
                'section' => [
                    'saveAsJson' => true,
                ],
                'hero' => Types::HERO,
                'intro' => Types::INTRO,
                'overviews' => Types::OVERVIEWS,
                'quote' => Types::QUOTE,
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
