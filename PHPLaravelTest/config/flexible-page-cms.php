<?php

return [
    /**
     * Class name of the App's Page model
     */
    'app_page_model_class' => 'App\Models\Page',

    /**
     * Array of categories available for pages. Display names on right
     */
    'page_categories' => [
        'main' => 'Main',
        'promo' => 'Promo',
        'essential' => 'Essential',
    ],

    /**
     * Default meta data to be used when none is explicitly set for a Page
     */
    'default_page_meta_title' => 'Brown Bear Car Wash',
    'default_page_meta_description' => '',
    'default_page_meta_keywords' => '',

    /**
     * Voyager-specific stuff (https://github.com/the-control-group/voyager)
     */
    'voyager' => [
        'pages_menu_item' => [
            'menu_id' => 1,
            'title' => 'Pages',
            'icon_class' => 'voyager-file-text',
            'color' => null,
            'parent_id' => null,
            'order' => 2,
            'parameters' => null,
        ],
    ],
];
