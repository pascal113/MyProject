<?php

namespace App\FlexiblePageCms;

use FPCS\FlexiblePageCms\ComponentTypes;
use FPCS\FlexiblePageCms\ContentBlocks;

class Types
{
    public const HERO = [
        'section' => [
            'titleType' => 'h2',
            'title' => 'Hero',
        ],
        'heading' => [
            'formfieldLabel' => 'Headline',
            'formfieldType' => 'text',
            'required' => true,
        ],
        'image' => [
            'formfieldLabel' => 'Image',
            'formfieldType' => 'images',
            'required' => true,
            'helperText' => '(1440x320px)',
        ],
    ];

    public const INTRO = [
        'section' => [
            'titleType' => 'h2',
            'title' => 'Intro',
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
    ];

    public const QUOTE = [
        'section' => [
            'titleType' => 'h2',
            'title' => 'Quote',
        ],
        'image' => [
            'formfieldLabel' => 'Image',
            'formfieldType' => 'images',
        ],
        'quote' => [
            'formfieldLabel' => 'Quote',
            'formfieldType' => 'text',
        ],
        'attribution' => [
            'formfieldLabel' => 'Attribution',
            'formfieldType' => 'text',
        ],
    ];

    public const RELATED_CONTENT = [
        'section' => [
            'titleType' => 'h2',
            'title' => 'Related Content',
        ],
        'image' => [
            'formfieldLabel' => 'Image',
            'formfieldType' => 'images',
        ],
        'heading' => [
            'formfieldLabel' => 'Headline',
            'formfieldType' => 'text',
        ],
        'paragraphs' => [
            'formfieldLabel' => 'Paragraph',
            'formfieldType' => 'paragraphs',
        ],
        'button' => ComponentTypes::BUTTON,
    ];

    public const GLOBAL_ASK_A_QUESTION = [
        'formfieldType' => 'related',
        'formfieldLabel' => 'Ask a Question',
        'sourcePagePath' => '/',
        'sourceContentKey' => 'askAQuestion',
    ];

    public const GLOBAL_NEWS = [
        'formfieldType' => 'related',
        'formfieldLabel' => 'News Stories',
        'sourcePagePath' => '/our-company/news',
        'sourceContentKey' => 'latestStories.items',
    ];

    public const OVERVIEWS = [
        'section' => [
            'titleType' => 'h2',
            'title' => 'Overviews',
        ],
        'items' => [
            'formfieldLabel' => '',
            'formfieldType' => 'repeating',
            'options' => [
                'layout' => 'stacked',
                'itemLabel' => 'Overview Section',
                'formfields' => [
                    'wrapperColorClass' => [
                        'formfieldLabel' => 'Background Color',
                        'formfieldType' => 'select',
                        'options' => [
                            'selectOptions' => [
                                ContentBlocks::WRAPPER_COLOR_WHITE => 'White',
                                ContentBlocks::WRAPPER_COLOR_BLUE => 'Blue',
                                ContentBlocks::WRAPPER_COLOR_GRAY => 'Gray',
                            ],
                        ],
                        'required' => true,
                    ],
                    'icon' => Types::ICON,
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'heading',
                        'required' => true,
                    ],
                    'image' => [
                        'formfieldLabel' => 'Image',
                        'formfieldType' => 'images',
                    ],
                    'paragraphs' => [
                        'formfieldLabel' => 'Paragraph',
                        'formfieldType' => 'paragraphs',
                        'required' => true,
                    ],
                    'button' => ComponentTypes::BUTTON,
                ],
            ],
        ],
    ];

    public const PRODUCT_ITEMS = [
        'formfieldLabel' => 'Products',
        'formfieldType' => 'repeating',
        'options' => [
            'itemLabel' => 'Product',
            'formfields' => [
                'id' => [
                    'formfieldLabel' => 'Select Product',
                    'formfieldType' => 'product',
                    'required' => true,
                ],
            ],
        ],
    ];

    public const WASH_GREEN = [
        'section' => [
            'titleType' => 'h2',
            'title' => 'Wash Green',
        ],
        'heading' => [
            'formfieldLabel' => 'Headline',
            'formfieldType' => 'text',
            'required' => true,
        ],
        'wysiwyg' => [
            'formfieldLabel' => 'Paragraph',
            'formfieldType' => 'wysiwyg',
        ],
        'button' => ComponentTypes::BUTTON,
    ];

    public const PROGRAM_INFO = [
        'section' => [
            'titleType' => 'h2',
            'title' => 'Program Information',
        ],
        'heading' => [
            'formfieldLabel' => 'Headline',
            'formfieldType' => 'text',
        ],
        'paragraphs' => [
            'formfieldLabel' => 'Paragraph',
            'formfieldType' => 'paragraphs',
        ],
        'button' => ComponentTypes::BUTTON,
    ];

    public const OPENINGS = [
        'section' => [
            'titleType' => 'h2',
            'title' => 'Openings',
        ],
        'heading' => [
            'formfieldLabel' => 'Headline',
            'formfieldType' => 'text',
            'required' => true,
        ],
        'icon' => self::ICON,
        'paragraphs' => [
            'formfieldLabel' => 'Paragraph',
            'formfieldType' => 'paragraphs',
        ],
        'button' => ComponentTypes::BUTTON,
    ];

    public const ICON = [
        'formfieldLabel' => 'Icon',
        'formfieldType' => 'icon',
        'required' => false,
    ];

    public const WASH_LEVELS = [
        'formfieldLabel' => 'Wash Levels',
        'formfieldType' => 'repeating',
        'options' => [
            'itemLabel' => 'Wash Level',
            'formfields' => [
                'title' => [
                    'formfieldLabel' => 'Title',
                    'formfieldType' => 'text',
                    'required' => true,
                ],
                'details' => [
                    'formfieldLabel' => 'Product Details',
                    'formfieldType' => 'wysiwyg',
                ],
                'images' => [
                    'formfieldLabel' => 'Image',
                    'formfieldType' => 'images',
                    'options' => [
                        'min' => 0,
                    ],
                ],
            ],
        ],
    ];

    public const VIDEO = [
        'formfieldLabel' => 'Video URL',
        'formfieldType' => 'text',
        'helperText' => 'Enter the URL of either an mp4 file hosted anywhere, or a YouTube embed URL such as https://www.youtube.com/embed/dQw4w9WgXcQ',
    ];

    public const NEWS_ITEM = [
        'date' => [
            'formfieldLabel' => 'Date',
            'formfieldType' => 'date',
        ],
        'title' => [
            'formfieldLabel' => 'Title',
            'formfieldType' => 'text',
            'required' => true,
        ],
        'publication' => [
            'formfieldLabel' => 'Publication',
            'formfieldType' => 'text',
        ],
        'url' => [
            'formfieldLabel' => 'Url',
            'formfieldType' => 'url',
            'required' => true,
        ],
    ];
}
