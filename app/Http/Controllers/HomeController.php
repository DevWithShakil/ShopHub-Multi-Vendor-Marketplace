<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['category.parent.parent'])
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->latest()
            ->take(50)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'base_price' => $product->base_price,
                    'discount_price' => $product->discount_price,
                    'thumb_image' => $product->thumb_image,
                    'category' => $product->category,
                ];
            });

        return Inertia::render('Home', [
            'products' => $products
        ]);
    }
}
