<?php

declare(strict_types=1);

namespace App\FlexiblePageCms\TemplateTypes\OurCompany;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\ComponentTypes;
use FPCS\FlexiblePageCms\TemplateType;

class IndexTemplateType extends TemplateType
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
                'ourHistory' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Our History',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'image' => [
                        'formfieldLabel' => 'Image',
                        'formfieldType' => 'images',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'button' => ComponentTypes::BUTTON,
                ],
                'leadership' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Leadership',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'button' => ComponentTypes::BUTTON,
                    'headshots' => [
                        'formfieldLabel' => 'Headshots',
                        'formfieldType' => 'images',
                        'options' => [
                            'min' => 1,
                        ],
                        'helperText' => '(215x215px)',
                    ],
                ],
                'careers' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Careers',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'image' => [
                        'formfieldLabel' => 'Image',
                        'formfieldType' => 'images',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'button' => ComponentTypes::BUTTON,
                ],
                'inTheNews' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'In the News',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'items' => array_merge(
                        Types::GLOBAL_NEWS,
                        [
                            'formfieldLevelLevel' => 'h4',
                            'isSection' => false,
                        ]
                    ),
                    'button' => ComponentTypes::BUTTON,
                ],
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
