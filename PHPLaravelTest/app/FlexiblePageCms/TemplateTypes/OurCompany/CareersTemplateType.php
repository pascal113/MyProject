<?php

declare(strict_types=1);

namespace App\FlexiblePageCms\TemplateTypes\OurCompany;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\ComponentTypes;
use FPCS\FlexiblePageCms\TemplateType;

class CareersTemplateType extends TemplateType
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
                'companyBenefits' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Company Benefits',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'videoUrl' => Types::VIDEO,
                    'wysiwyg' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'wysiwyg',
                    ],
                ],
                'quote' => Types::QUOTE,
                'employeeSupport' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Employee Support',
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
                'openings' => Types::OPENINGS,
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
