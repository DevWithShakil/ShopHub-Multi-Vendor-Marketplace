<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the home page with products.
     */
   public function index()
{
    $products = Product::with(['category.parent.parent'])
        ->where('is_active', true)
        ->where('approval_status', 'approved')
        ->withAvg('reviews as reviews_avg_rating', 'rating')
        ->withCount('reviews')
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
                'reviews_avg_rating' => $product->reviews_avg_rating,
                'reviews_count' => $product->reviews_count,
            ];
        });

    return Inertia::render('Home',[
        'products' => $products

    ]);
}

    /**
     *  API Search Method for Navbar Dropdown
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $products = Product::with('category')
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->whereRaw("LOWER(name->>'en') LIKE ?", ['%' . strtolower($query) . '%'])
                  ->orWhereRaw("LOWER(name->>'bn') LIKE ?", ['%' . strtolower($query) . '%'])
                  ->orWhereHas('category', function($catQ) use ($query) {
                      $catQ->whereRaw("LOWER(name->>'en') LIKE ?", ['%' . strtolower($query) . '%']);
                  });
            })
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->thumb_image,
                    'price' => $product->base_price,
                    'category' => $product->category ? $product->category->name : null,
                ];
            });

        return response()->json($products);
    }
}
