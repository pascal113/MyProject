<?php


namespace App\Http\Controllers;

use App\Services\GatewayService;
use Illuminate\Http\Request;

class TermsContentController extends Controller
{
    /**
     * Show the Terms
     */
    public function showMembershipTerms(Request $request)
    {
        $content = GatewayService::getCurrentTermsContent();

        if (empty($content)) {
            self::abort(500);
        }

        return parent::view('terms.wash-clubs', compact('content'));
    }
}
