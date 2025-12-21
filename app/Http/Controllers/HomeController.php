<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'base_price' => $product->base_price,
                    'thumb_image' => $product->thumb_image,
                    'category' => $product->category ? [
                        'name' => $product->category->name
                    ] : null,
                ];
            });

        return Inertia::render('Home', [
            'products' => $products
        ]);
    }
}
