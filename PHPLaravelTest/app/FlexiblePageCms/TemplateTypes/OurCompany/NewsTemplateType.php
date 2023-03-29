<?php

namespace App\FlexiblePageCms\TemplateTypes\OurCompany;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\ComponentTypes;
use FPCS\FlexiblePageCms\TemplateType;

class NewsTemplateType extends TemplateType
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
                    'titleType' => 'h1',
                ],
                'hero' => Types::HERO,
                'intro' => Types::INTRO,
                'latestStories' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Latest Stories',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'items' => [
                        'formfieldLabel' => 'Latest Stories',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Story',
                            'formfields' => Types::NEWS_ITEM,
                        ],
                    ],
                ],
                'quote' => Types::QUOTE,
                'pressCenter' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Press Center',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'icon' => Types::ICON,
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'button' => ComponentTypes::BUTTON,
                ],
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
