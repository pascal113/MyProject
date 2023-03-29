<?php

namespace App\FlexiblePageCms\TemplateTypes\OurCompany;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\TemplateType;

class OurHistoryTemplateType extends TemplateType
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
                    'topImages' => [
                        'formfieldLabel' => 'Top Images',
                        'formfieldType' => 'images',
                        'options' => [
                            'max' => 2,
                        ],
                        'helperText' => '(525x345px)',
                    ],
                    'topWysiwyg' => [
                        'formfieldLabel' => 'Top Paragraph',
                        'formfieldType' => 'wysiwyg',
                    ],
                    'middleImages' => [
                        'formfieldLabel' => 'Middle Images',
                        'formfieldType' => 'images',
                        'options' => [
                            'max' => 2,
                        ],
                        'helperText' => '(525x345px)',
                    ],
                    'bottomWysiwyg' => [
                        'formfieldLabel' => 'Bottom Paragraph',
                        'formfieldType' => 'wysiwyg',
                    ],
                    'bottomImages' => [
                        'formfieldLabel' => 'Bottom Images',
                        'formfieldType' => 'images',
                        'options' => [
                            'max' => 2,
                        ],
                        'helperText' => '(525x345px)',
                    ],
                ],
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
