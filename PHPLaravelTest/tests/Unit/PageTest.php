<?php

namespace Tests\Unit;

use App\FlexiblePageCms\TemplateTypes\HomeTemplateType;
use App\Models\Page;
use Tests\TestCase;

/**
 * @covers \App\Models\Page::
 */
class PageTest extends TestCase
{
    /**
     * Test that ->templateType attribute accessor works correctly
     *
     * @return void
     */
    public function testTemplateTypeAttribute()
    {
        $page = new Page([ 'template' => 'home' ]);

        $this->assertEquals($page->templateType, new HomeTemplateType());
    }

    /**
     * Test that ->prepareContentForPublicDisplay() method works correctly for a valid route
     *
     * @return void
     */
    public function testSanitizeValidRoute()
    {
        $page = new Page([
            'template' => 'home',
            'content' => (object)[
                'welcome' => (object)[
                    'button' => (object)[
                        'route' => 'home',
                        'text' => 'button text',
                    ],
                ],
            ],
        ]);

        $this->assertEquals($page->prepareContentForPublicDisplay()->content->welcome->button->route, 'home');
        $this->assertEquals($page->prepareContentForPublicDisplay()->content->welcome->button->text, 'button text');
    }

    /**
     * Test that ->prepareContentForPublicDisplay() method works correctly for a invalid route
     *
     * @return void
     */
    public function testSanitizeInvalidRoute()
    {
        $page = new Page([
            'template' => 'home',
            'content' => (object)[
                'welcome' => (object)[
                    'button' => (object)[
                        'route' => 'invalid route name',
                        'text' => 'button text',
                    ],
                ],
            ],
        ]);

        $this->assertNull($page->prepareContentForPublicDisplay()->content->welcome->button->route);
        $this->assertEquals($page->prepareContentForPublicDisplay()->content->welcome->button->text, 'button text');
    }
}
