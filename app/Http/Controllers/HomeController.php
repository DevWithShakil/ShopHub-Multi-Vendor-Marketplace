<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request; // ✅ Request ইমপোর্ট করা হলো
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

    /**
     * ✅ API Search Method for Navbar Dropdown
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
                // PostgreSQL JSON Case-Insensitive Search
                $q->whereRaw("LOWER(name->>'en') LIKE ?", ['%' . strtolower($query) . '%'])
                  ->orWhereRaw("LOWER(name->>'bn') LIKE ?", ['%' . strtolower($query) . '%'])
                  // Category Name Search
                  ->orWhereHas('category', function($catQ) use ($query) {
                      $catQ->whereRaw("LOWER(name->>'en') LIKE ?", ['%' . strtolower($query) . '%']);
                  });
            })
            ->take(5) // সর্বোচ্চ ৫টি রেজাল্ট
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
