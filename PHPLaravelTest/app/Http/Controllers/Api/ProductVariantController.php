<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductVariantCollection;
use App\Http\Resources\ProductVariantResource;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ProductVariantController extends ApiController
{
    /**
     * Return all ProductVariants
     */
    public function index(): JsonResponse
    {
        $variants = ProductVariant::all();

        return Response::json([ 'data' => new ProductVariantCollection($variants) ])->setStatusCode(200);
    }

    /**
     * Return a single ProductVariant
     */
    public function show(string $slugOrWashConnectProgramId): JsonResponse
    {
        $variant = ProductVariant::where('slug', $slugOrWashConnectProgramId)
            ->orWhere('wash_connect_program_id', $slugOrWashConnectProgramId)
            ->first();

        if (!$variant) {
            return Response::json([ 'message' => 'Not Found' ])->setStatusCode(404);
        }

        return Response::json([ 'data' => new ProductVariantResource($variant) ])->setStatusCode(200);
    }
}
