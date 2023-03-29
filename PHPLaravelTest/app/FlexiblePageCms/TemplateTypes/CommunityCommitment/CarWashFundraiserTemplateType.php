<?php

namespace App\FlexiblePageCms\TemplateTypes\CommunityCommitment;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\ComponentTypes;
use FPCS\FlexiblePageCms\ContentBlocks;
use FPCS\FlexiblePageCms\TemplateType;

class CarWashFundraiserTemplateType extends TemplateType
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
                'interestedInGettingStarted' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Program Information',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'wysiwyg' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'wysiwyg',
                    ],
                    'button' => ComponentTypes::BUTTON,
                ],
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
