<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with(['category', 'vendor', 'variants'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'base_price' => $product->base_price,
            'discount_price' => $product->discount_price,
            'thumb_image' => $product->thumb_image,
            'gallery_images' => $product->gallery_images,
            'has_variants' => $product->has_variants,
            'category' => $product->category ? ['name' => $product->category->name] : null,
            'vendor' => $product->vendor ? ['shop_name' => $product->vendor->shop_name] : null,
            'variants' => $product->variants
        ];

        return Inertia::render('ProductDetails', [
            'product' => $productData
        ]);
    }
}
