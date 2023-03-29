<?php

namespace App\FlexiblePageCms\TemplateTypes\AboutOurWashes;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\ContentBlocks;
use FPCS\FlexiblePageCms\TemplateType;

class TopTierGasTemplateType extends TemplateType
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
                'contentBlocks' => [
                    'formfieldLabel' => 'Flexible Content Blocks',
                    'formfieldType' => ContentBlocks::CONTENT_BLOCKS,
                    'options' => [
                        'availableComponents' => ContentBlocks::AVAILABLE_COMPONENTS,
                    ],
                ],
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
