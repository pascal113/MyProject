<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Models\Page;
use FPCS\FlexiblePageCms\Traits\Voyager\AdminPageController;
use Illuminate\Support\Facades\Mail;

class PageController extends AdminController
{
    use AdminPageController;

    /**
     * @param Page $unpublishedPage
     */
    public function afterUnpublish(Page $unpublishedPage)
    {
        $affectedPages = $unpublishedPage->pages_that_link_to_this;

        if (sizeof($affectedPages) > 0) {
            Mail::send(
                [
                    'html' => 'emails.page-unpublished-html',
                    'text' => 'emails.page-unpublished-text',
                ],
                [
                    'unpublishedPageTitle' => $unpublishedPage->title,
                    'affectedPages' => $affectedPages,
                ],
                function ($message) {
                    $message->to(config('mail.support.address'), config('mail.support.name'))
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject('Page unpublished on brownbear.com');
                }
            );
        }
    }
}
