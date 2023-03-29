<?php

namespace App\FlexiblePageCms\TemplateTypes;

use FPCS\FlexiblePageCms\ComponentTypes;
use FPCS\FlexiblePageCms\TemplateType;

class HomeTemplateType extends TemplateType
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
                'carousel' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Hero Carousel',
                    ],
                    'items' => [
                        'formfieldLabel' => 'Slides',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Slide',
                            'formfields' => [
                                'heading' => [
                                    'formfieldLabel' => 'Headline',
                                    'formfieldType' => 'text',
                                    'required' => true,
                                ],
                                'desktopBackgroundImage' => [
                                    'formfieldLabel' => 'Desktop Background Image',
                                    'formfieldType' => 'images',
                                    'required' => true,
                                    'helperText' => '(1440x600px)',
                                ],
                                'mobileBackgroundImage' => [
                                    'formfieldLabel' => 'Mobile Background Image',
                                    'formfieldType' => 'images',
                                    'required' => true,
                                    'helperText' => '(800x600px)',
                                ],
                                'image' => [
                                    'formfieldLabel' => 'Content Image',
                                    'formfieldType' => 'images',
                                ],
                                'button' => ComponentTypes::BUTTON,
                            ],
                        ],
                    ],
                ],
                'welcome' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Welcome',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Intro',
                        'formfieldType' => 'paragraphs',
                    ],
                    'button' => ComponentTypes::BUTTON,
                ],
                'cards' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Cards',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'items' => [
                        'formfieldLabel' => 'Cards',
                        'formfieldType' => 'repeating',
                        'options' => [
                            'itemLabel' => 'Card',
                            'formfields' => [
                                'heading' => [
                                    'formfieldLabel' => 'Headline',
                                    'formfieldType' => 'text',
                                    'required' => true,
                                ],
                                'image' => [
                                    'formfieldLabel' => 'Image',
                                    'formfieldType' => 'images',
                                    'required' => true,
                                    'helperText' => '(260x190px)',
                                ],
                                'description' => [
                                    'formfieldLabel' => 'Description',
                                    'formfieldType' => 'paragraphs',
                                ],
                                'button' => ComponentTypes::BUTTON,
                            ],
                        ],
                    ],
                ],
                'askAQuestion' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Ask a Question',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
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
