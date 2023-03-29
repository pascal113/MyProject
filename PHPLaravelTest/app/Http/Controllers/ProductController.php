<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Product detail page
     */
    public function show(string $id, string $slugifiedName = null)
    {
        $product = Product::findOrFail($id);

        return parent::view('products.show', compact('product'));
    }
}
