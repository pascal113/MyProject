<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use FPCS\FlexiblePageCms\Services\CmsRoute;
use Illuminate\Http\Response;

class CheckWashCardBalanceController extends Controller
{
    /**
     * Index page
     */
    public function index(?string $cardNumber = null): Response
    {
        $page = CmsRoute::getPageByPathOrFail('wash-card');
        $page->prepareContentForPublicDisplay();

        return parent::view('flexible-page-cms.templates.wash-cards.check-balance', [
            'cardNumber' => $cardNumber,
            'page' => $page,
        ]);
    }
}
