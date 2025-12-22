<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['category']);

        if ($request->has('category') && $request->category != null) {
            $categoryName = $request->category;
            $locale = App::getLocale();

            $query->whereHas('category', function ($q) use ($categoryName, $locale) {
                $q->whereRaw("name->>'$locale' = ?", [$categoryName])
                  ->orWhereRaw("name->>'en' = ?", [$categoryName]);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $locale = App::getLocale();

            $query->where(function($q) use ($search, $locale) {
                $q->whereRaw("name->>'$locale' ILIKE ?", ['%' . $search . '%'])
                  ->orWhereRaw("name->>'en' ILIKE ?", ['%' . $search . '%']);
            });
        }

        $query->latest();

        $products = $query->paginate(12)->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categoryName' => $request->category ?? 'All Products',
        ]);
    }

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
