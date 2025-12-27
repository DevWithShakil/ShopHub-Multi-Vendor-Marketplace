<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Slider;
use App\Models\FlashSale;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Sliders
        $slides = Slider::with('product')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($slide) {
                // ... (আপনার স্লাইডার ম্যাপিং লজিক ঠিক আছে) ...
                return [
                    'id' => $slide->id,
                    'image' => $slide->image ? asset('storage/' . $slide->image) : null,
                    'price' => $slide->product ? '৳' . $slide->product->base_price : '',
                    'slug' => $slide->product ? $slide->product->slug : '#',
                    // ... বাকি ফিল্ড ...
                ];
            });

        // 2. Categories
        $categories = Category::whereNull('parent_id')
            ->with(['children.children.children'])
            ->select('id', 'name', 'slug')
            ->get();

        // 3. ✅ New Arrivals (ফিক্সড)
        // এটিই মেইন কোড যা ফ্রন্টএন্ডে যাবে। লেটেস্ট ১০টি প্রোডাক্ট নেওয়া হচ্ছে।
        $newArrivals = Product::with('category')
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->latest() // created_at desc
            ->take(10) // ১০টি প্রোডাক্ট (ফ্রন্টএন্ডে স্ক্রল করলে ১০টি ভালো দেখাবে)
            ->get();

        // 4. Top Rated
        $topRated = Product::with('category')
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->withCount('reviews')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->whereHas('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(8)
            ->get();

        // 5. Best Sellers
        $bestSellers = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->where('products.is_active', true)
            ->where('products.approval_status', 'approved')
            ->groupBy('products.id')
            ->with('category')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->orderByDesc('total_sold')
            ->take(8)
            ->get();

        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::where('is_active', true)
                ->where('approval_status', 'approved')
                ->with('category')
                ->withAvg('reviews as reviews_avg_rating', 'rating')
                ->withCount('reviews')
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        // 6. Flash Sale
        $flashSale = FlashSale::with(['products' => function($query) {
                $query->where('is_active', true)
                      ->where('approval_status', 'approved')
                      ->with('category')
                      ->withPivot('discount_price', 'stock_limit', 'sold')
                      ->withAvg('reviews as reviews_avg_rating', 'rating')
                      ->withCount('reviews')
                      ->take(10);
            }])
            ->where('status', true)
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->latest()
            ->first();

        // 7. Brands
        $brands = Brand::where('is_active', true)
            ->select('id', 'name', 'logo')
            ->latest()
            ->take(20)
            ->get();

        // 8. General Products (For optimization, removed unnecessary map logic if
        $products = Product::with(['category'])
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(20)
            ->get();


        return Inertia::render('Home', [
            'slides' => $slides,
            'categories' => $categories,
            'newArrivals' => $newArrivals, //
            'topRated' => $topRated,
            'bestSellers' => $bestSellers,
            'flashSale' => $flashSale,
            'products' => $products,
            'brands' => $brands,
        ]);
    }

    // ... search & trackOrder methods (unchanged)
}
