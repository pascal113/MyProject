<?php


namespace App\FlexiblePageCms\TemplateTypes\WashClubs;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\ComponentTypes;
use FPCS\FlexiblePageCms\TemplateType;

class UnlimitedWashClubTemplateType extends TemplateType
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
                'main' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Main',
                    ],
                    'images' => [
                        'formfieldLabel' => 'Images',
                        'formfieldType' => 'images',
                        'options' => [
                            'min' => 1,
                            'max' => 2,
                        ],
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'callToAction' => [
                        'formfieldLabel' => 'Call to Action',
                        'formfieldType' => 'text',
                    ],
                ],
                'washClubLevels' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Wash Club Levels',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'levels' => Types::WASH_LEVELS,
                    'paragraphs2' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                ],
                'purchasingOptions' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Purchasing Options',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'products' => Types::PRODUCT_ITEMS,

                ],
                'findTunnelWash' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Find a Tunnel Wash',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'image' => [
                        'formfieldLabel' => 'Image',
                        'formfieldType' => 'images',
                    ],
                    'button' => ComponentTypes::BUTTON,
                ],
                'washGreen' => Types::WASH_GREEN,
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
