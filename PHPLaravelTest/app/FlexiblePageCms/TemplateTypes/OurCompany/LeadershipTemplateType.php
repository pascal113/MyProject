<?php

declare(strict_types=1);

namespace App\FlexiblePageCms\TemplateTypes\OurCompany;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\ComponentTypes;
use FPCS\FlexiblePageCms\TemplateType;

class LeadershipTemplateType extends TemplateType
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
                'corporateExecutivesSection' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Corporate Executives Section',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'people' => [
                        'formfieldLabel' => 'People',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Person',
                            'formfields' => [
                                'photo' => [
                                    'formfieldLabel' => 'Photo',
                                    'formfieldType' => 'images',
                                    'required' => true,
                                    'helperText' => '(215x215px)',
                                ],
                                'name' => [
                                    'formfieldLabel' => 'Name',
                                    'formfieldType' => 'text',
                                    'required' => true,
                                ],
                                'jobTitle' => [
                                    'formfieldLabel' => 'Job Title',
                                    'formfieldType' => 'text',
                                    'required' => true,
                                ],
                                'bio' => [
                                    'formfieldLabel' => 'Bio',
                                    'formfieldType' => 'paragraphs',
                                ],
                            ],
                        ],
                    ],
                ],
                'seniorManagersSection' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Senior Managers Section',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'people' => [
                        'formfieldLabel' => 'People',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Person',
                            'formfields' => [
                                'photo' => [
                                    'formfieldLabel' => 'Photo',
                                    'formfieldType' => 'images',
                                    'required' => true,
                                    'helperText' => '(215x215px)',
                                ],
                                'name' => [
                                    'formfieldLabel' => 'Name',
                                    'formfieldType' => 'text',
                                ],
                                'jobTitle' => [
                                    'formfieldLabel' => 'Job Title',
                                    'formfieldType' => 'text',
                                ],
                                'bio' => [
                                    'formfieldLabel' => 'Bio',
                                    'formfieldType' => 'paragraphs',
                                ],
                            ],
                        ],
                    ],
                ],
                'areaManagersSection' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Area Managers Section',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'people' => [
                        'formfieldLabel' => 'People',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Person',
                            'formfields' => [
                                'photo' => [
                                    'formfieldLabel' => 'Photo',
                                    'formfieldType' => 'images',
                                    'required' => true,
                                    'helperText' => '(215x215px)',
                                ],
                                'name' => [
                                    'formfieldLabel' => 'Name',
                                    'formfieldType' => 'text',
                                ],
                                'jobTitle' => [
                                    'formfieldLabel' => 'Job Title',
                                    'formfieldType' => 'text',
                                ],
                                'bio' => [
                                    'formfieldLabel' => 'Bio',
                                    'formfieldType' => 'paragraphs',
                                ],
                            ],
                        ],
                    ],
                ],
                'siteManagersSection' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Site Managers Section',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'people' => [
                        'formfieldLabel' => 'People',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Person',
                            'formfields' => [
                                'photo' => [
                                    'formfieldLabel' => 'Photo',
                                    'formfieldType' => 'images',
                                    'required' => true,
                                    'helperText' => '(215x215px)',
                                ],
                                'name' => [
                                    'formfieldLabel' => 'Name',
                                    'formfieldType' => 'text',
                                ],
                                'jobTitle' => [
                                    'formfieldLabel' => 'Job Title',
                                    'formfieldType' => 'text',
                                ],
                                'bio' => [
                                    'formfieldLabel' => 'Bio',
                                    'formfieldType' => 'paragraphs',
                                ],
                            ],
                        ],
                    ],
                ],

                'joinOurTeam' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Join Our Team',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                    ],
                    'videoUrl' => Types::VIDEO,
                    'button' => ComponentTypes::BUTTON,
                ],
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
