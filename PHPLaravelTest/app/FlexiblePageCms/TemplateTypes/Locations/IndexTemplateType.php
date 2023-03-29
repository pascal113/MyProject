<?php

namespace App\FlexiblePageCms\TemplateTypes\Locations;

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
                    'titleType' => 'h1',
                ],
                'hero' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Hero',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                ],
                'intro' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Intro',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                ],
            ],
        ];
    }
}
