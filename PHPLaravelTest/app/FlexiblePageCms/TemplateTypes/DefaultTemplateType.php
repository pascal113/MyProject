<?php

namespace App\FlexiblePageCms\TemplateTypes;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\TemplateType;

class DefaultTemplateType extends TemplateType
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
                'main' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Main',
                    ],
                    'alternatingContentBlocks' => [
                        'formfieldLabel' => 'Alternating Content Blocks',
                        'formfieldType' => 'repeating',
                        'helperText' => 'Content blocks will have an alternating background color.',
                        'options' => [
                            'layout' => 'stacked',
                            'itemLabel' => 'Content Block',
                            'formfields' => [
                                'heading' => [
                                    'formfieldLabel' => 'Headline',
                                    'formfieldType' => 'text',
                                    'toggleable' => true,
                                ],
                                'image' => [
                                    'formfieldLabel' => 'Image',
                                    'formfieldType' => 'images',
                                    'options' => [
                                        'max' => null,
                                    ],
                                    'toggleable' => true,
                                ],
                                'description' => [
                                    'formfieldLabel' => 'Paragraphs',
                                    'formfieldType' => 'wysiwyg',
                                    'toggleable' => true,
                                ],
                            ],
                        ],
                    ],
                    'unifiedContentBlocks' => [
                        'formfieldLabel' => 'Unified Content Blocks',
                        'formfieldType' => 'repeating',
                        'helperText' => 'Content blocks will have the same background color.',
                        'options' => [
                            'layout' => 'stacked',
                            'itemLabel' => 'Content Block',
                            'formfields' => [
                                'heading' => [
                                    'formfieldLabel' => 'Headline',
                                    'formfieldType' => 'text',
                                    'toggleable' => true,
                                ],
                                'image' => [
                                    'formfieldLabel' => 'Image',
                                    'formfieldType' => 'images',
                                    'options' => [
                                        'max' => null,
                                    ],
                                    'toggleable' => true,
                                ],
                                'description' => [
                                    'formfieldLabel' => 'Paragraphs',
                                    'formfieldType' => 'wysiwyg',
                                    'toggleable' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
