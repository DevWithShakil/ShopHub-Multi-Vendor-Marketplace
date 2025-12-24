<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Slider;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Fetch Dynamic Sliders from Database
        $slides = Slider::with('product')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($slide) {
                $imageUrl = $slide->image ? asset('storage/' . $slide->image) : null;
                $price = $slide->product ? '৳' . $slide->product->base_price : '';
                $slug = $slide->product ? $slide->product->slug : '#';
                $bgClass = "bg-gradient-radial " . ($slide->bg_color ?? 'from-slate-900 via-purple-900 to-slate-900');

                $btnClass = 'bg-indigo-600 hover:bg-indigo-500';
                if (str_contains($slide->bg_color ?? '', 'orange')) {
                    $btnClass = 'bg-orange-600 hover:bg-orange-500';
                } elseif (str_contains($slide->bg_color ?? '', 'emerald')) {
                    $btnClass = 'bg-emerald-600 hover:bg-emerald-500';
                }

                return [
                    'id' => $slide->id,
                    'tag' => $slide->title ?? 'Welcome',
                    'name' => $slide->heading ?? 'ShopHub Store',
                    'desc' => $slide->description,
                    'image' => $imageUrl,
                    'price' => $price,
                    'slug' => $slug,
                    'bgClass' => $bgClass,
                    'btnClass' => $btnClass,
                    'textGradient' => 'from-white via-gray-200 to-gray-400',
                    'highlight' => '',
                ];
            });

        // 2. Fetch Products with Root Category Logic
        // We load parent relationships up to 3 levels deep to find the root category
        $products = Product::with(['category.parent.parent'])
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(100) // Increased limit to ensure we have enough products for grouping
            ->get()
            ->map(function ($product) {
                // 🔥 Logic to find the Root/Mother Category
                $rootCategory = $product->category;
                while ($rootCategory && $rootCategory->parent) {
                    $rootCategory = $rootCategory->parent;
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'base_price' => $product->base_price,
                    'discount_price' => $product->discount_price,
                    'thumb_image' => $product->thumb_image,
                    'category' => $product->category, // Original category for display on card
                    'root_category' => $rootCategory ? [ // ✅ Mother Category for Grouping
                        'name' => $rootCategory->name,
                        'slug' => $rootCategory->slug
                    ] : null,
                    'reviews_avg_rating' => $product->reviews_avg_rating,
                    'reviews_count' => $product->reviews_count,
                    'vendor_id' => $product->vendor_id,
                ];
            });

        // 3. Hierarchical Categories (Parent -> Children -> GrandChildren -> GreatGrandChildren)
        // This is for the Mega Menu
        $categories = Category::whereNull('parent_id') // Level 1 (Mother)
            ->with([
                'children' => function($q) { // Level 2
                    $q->select('id', 'name', 'parent_id', 'slug')
                      ->with(['children' => function($q2) { // Level 3
                          $q2->select('id', 'name', 'parent_id', 'slug')
                             ->with(['children' => function($q3) { // Level 4
                                 $q3->select('id', 'name', 'parent_id', 'slug');
                             }]);
                      }]);
                }
            ])
            ->select('id', 'name', 'slug')
            ->get();

        return Inertia::render('Home', [
            'products' => $products,
            'slides' => $slides,
            'categories' => $categories,
        ]);
    }

    public function search(Request $request) {
        $query = $request->input('query');
        if (!$query) return response()->json([]);

        $products = Product::with('category')->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->whereRaw("LOWER(name->>'en') LIKE ?", ['%' . strtolower($query) . '%'])
                  ->orWhereRaw("LOWER(name->>'bn') LIKE ?", ['%' . strtolower($query) . '%']);
            })->take(5)->get()->map(function ($p) {
                return [
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'image' => $p->thumb_image,
                    'price' => $p->base_price,
                    'category' => $p->category?->name,
                    'vendor_id' => $p->vendor_id
                ];
            });

        return response()->json($products);
    }

    public function trackOrder(Request $request) {
        $order = null;
        if ($request->isMethod('post')) {
            $request->validate(['invoice_no' => 'required|string|exists:orders,invoice_no']);
            $order = Order::with(['items.product'])->where('invoice_no', $request->invoice_no)->first();
        }
        return Inertia::render('TrackOrder', ['order' => $order, 'filters' => $request->only('invoice_no')]);
    }
}
