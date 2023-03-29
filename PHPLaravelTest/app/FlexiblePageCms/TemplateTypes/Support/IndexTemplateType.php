<?php

namespace App\FlexiblePageCms\TemplateTypes\Support;

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
                'faq' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'FAQ',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'items' => [
                        'formfieldLabel' => 'FAQ Categories',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'layout' => 'stacked',
                            'itemLabel' => 'FAQ Category',
                            'formfields' => [
                                'heading' => [
                                    'formfieldLabel' => 'Title',
                                    'formfieldType' => 'text',
                                    'required' => true,
                                ],
                                'wysiwyg' => [
                                    'formfieldLabel' => 'Questions & Answers',
                                    'formfieldType' => 'wysiwyg',
                                    'required' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                'contactUs' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Contact Us',
                    ],
                    'icon' => Types::ICON,
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'button' => ComponentTypes::BUTTON,
                ],
            ],
        ];
    }
}
