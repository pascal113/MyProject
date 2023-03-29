<?php

namespace App\FlexiblePageCms\TemplateTypes\Shop;

use App\FlexiblePageCms\Types;
use FPCS\FlexiblePageCms\TemplateType;

class ProductsTemplateType extends TemplateType
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
                'products' => [
                    'section' => [
                        'titleType' => 'h2',
                        'title' => 'Products',
                    ],
                    'heading' => [
                        'formfieldLabel' => 'Headline',
                        'formfieldType' => 'text',
                        'required' => true,
                    ],
                    'items' => Types::PRODUCT_ITEMS,
                ],
                'quote' => Types::QUOTE,
                'relatedContent' => Types::RELATED_CONTENT,
                'askAQuestion' => Types::GLOBAL_ASK_A_QUESTION,
            ],
        ];
    }
}
