<?php

namespace App\FlexiblePageCms\TemplateTypes\Support;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\TemplateType;

class ContactUsTemplateType extends TemplateType
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
                'phone' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Phone',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'icon' => Types::ICON,
                    'items' => [
                        'formfieldLabel' => 'Contact Methods',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Contact Method',
                            'formfields' => [
                                'heading' => [
                                    'formfieldLabel' => 'Headline',
                                    'formfieldType' => 'text',
                                    'required' => true,
                                ],
                                'phoneNumber' => [
                                    'formfieldLabel' => 'Phone Number',
                                    'formfieldType' => 'text',
                                    'required' => true,
                                ],
                                'wysiwyg' => [
                                    'formfieldLabel' => 'Reasons for Contact',
                                    'formfieldType' => 'wysiwyg',
                                    'required' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                'snailMail' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Snail Mail',
                    ],
                    'icon' => Types::ICON,
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Contact Address',
                        'formfieldType' => 'paragraphs',
                        'required' => true,
                    ],
                ],
                'mailingList' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Mailing List',
                    ],
                    'icon' => Types::ICON,
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraphs',
                        'formfieldType' => 'paragraphs',
                        'required' => true,
                    ],
                ],
            ],
        ];
    }
}
