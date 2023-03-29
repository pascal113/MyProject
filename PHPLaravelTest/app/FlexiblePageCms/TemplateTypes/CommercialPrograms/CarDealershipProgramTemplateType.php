<?php

namespace App\FlexiblePageCms\TemplateTypes\CommercialPrograms;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\ContentBlocks;
use FPCS\FlexiblePageCms\TemplateType;

class CarDealershipProgramTemplateType extends TemplateType
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
                'contentBlocks' => [
                    'formfieldLabel' => 'Flexible Content Blocks',
                    'formfieldType' => ContentBlocks::CONTENT_BLOCKS,
                    'options' => [
                        'availableComponents' => ContentBlocks::AVAILABLE_COMPONENTS,
                    ],
                ],
                'participatingDealerships' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Participating Dealerships',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'subHeading' => [
                        'formfieldLabel' => 'Subhead',
                        'formfieldType' => 'text',
                    ],
                    'image' => [
                        'formfieldLabel' => 'Image',
                        'formfieldType' => 'images',
                    ],
                    'dealershipsSubheading' => [
                        'formfieldLabel' => 'Dealerships Subhead',
                        'formfieldType' => 'text',
                    ],
                    'dealershipLogos' => [
                        'formfieldLabel' => 'Dealership Logos',
                        'formfieldType' => 'images_linked',
                        'options' => [
                            'min' => 1,
                        ],
                    ],
                ],
                'programInfo' => Types::PROGRAM_INFO,
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
