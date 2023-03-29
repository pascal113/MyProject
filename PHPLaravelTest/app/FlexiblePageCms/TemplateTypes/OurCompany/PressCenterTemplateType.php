<?php

namespace App\FlexiblePageCms\TemplateTypes\OurCompany;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\TemplateType;

class PressCenterTemplateType extends TemplateType
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
                'pressContacts' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Press Contacts',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'icon' => Types::ICON,
                    'email' => [
                        'formfieldLabel' => 'Email',
                        'formfieldType' => 'text',
                    ],
                    'phoneNumber' => [
                        'formfieldLabel' => 'Phone Number',
                        'formfieldType' => 'text',
                    ],
                ],
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
