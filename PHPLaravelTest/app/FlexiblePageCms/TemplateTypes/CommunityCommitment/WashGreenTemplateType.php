<?php

namespace App\FlexiblePageCms\TemplateTypes\CommunityCommitment;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\TemplateType;

class WashGreenTemplateType extends TemplateType
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
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'videoUrl' => Types::VIDEO,
                    'videoPosterImage' => [
                        'formfieldLabel' => 'Image',
                        'formfieldType' => 'images',
                    ],
                    'wysiwyg' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'wysiwyg',
                    ],
                ],
                'environmentalBenefits' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Environmental Benefits',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'icon' => Types::ICON,
                    'items' => [
                        'formfieldLabel' => 'Items',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Item',
                            'formfields' => [
                                'image' => [
                                    'formfieldLabel' => 'Image',
                                    'formfieldType' => 'images',
                                    'options' => [
                                        'min' => 0,
                                        'max' => 1,
                                    ],
                                ],
                                'heading' => [
                                    'formfieldLabel' => 'Headline',
                                    'formfieldType' => 'text',
                                ],
                                'wysiwyg' => [
                                    'formfieldLabel' => 'Paragraph',
                                    'formfieldType' => 'wysiwyg',
                                ],
                            ],
                        ],
                    ],
                ],
                'additionalEnvironmentalBenefits' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Additional Environmental Benefits',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'image' => [
                        'formfieldLabel' => 'Image',
                        'formfieldType' => 'images',
                    ],
                    'heading1' => [
                        'formfieldLabel' => 'Subhead',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                ],
                'quote' => Types::QUOTE,
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
