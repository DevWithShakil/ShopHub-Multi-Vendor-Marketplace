<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Slider;
use App\Models\FlashSale;
use App\Models\Brand;
use App\Models\Testimonial;
use App\Models\PromoCode;
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

        // 2. Categories
        $categories = Category::whereNull('parent_id')
            ->with(['children.children.children'])
            ->select('id', 'name', 'slug')
            ->get();

        // 3. New Arrivals
        $newArrivals = Product::with('category')
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(10)
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
            $bestSellers = Product::with('category')
                ->where('is_active', true)
                ->where('approval_status', 'approved')
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

        // 8. General Products
        $products = Product::with(['category'])
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(20)
            ->get();

        // 9. Testimonials Logic Added Here
        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        // Active Promos (Coupons)
        $activePromos = PromoCode::with('vendor')
            ->where('is_active', true)
            ->whereDate('end_date', '>=', now())
            ->latest()
            // ->take(4)
            ->get()
            ->map(function ($promo) {
                return [
                    'id' => $promo->id,
                    'code' => $promo->code,
                    'type' => $promo->type,
                    'value' => $promo->value,
                    'vendor_name' => $promo->vendor ? $promo->vendor->shop_name : 'Global',
                    'vendor_logo' => $promo->vendor ? asset('storage/'.$promo->vendor->logo) : null,
                    'expires_at' => $promo->end_date,
                    'vendor_id' => $promo->vendor ? $promo->vendor->id : null,
                ];
            });
        return Inertia::render('Home', [
            'slides' => $slides,
            'categories' => $categories,
            'newArrivals' => $newArrivals,
            'topRated' => $topRated,
            'bestSellers' => $bestSellers,
            'flashSale' => $flashSale,
            'products' => $products,
            'brands' => $brands,
            'testimonials' => $testimonials,
            'activePromos' => $activePromos,
        ]);
    }

    public function search(Request $request) {
        $query = $request->input('query');
        if (!$query) return response()->json([]);

        $products = Product::with('category')
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhereRaw("LOWER(name) LIKE ?", ['%' . strtolower($query) . '%']);
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
public function offers()
{
    $promos = PromoCode::with('vendor')
        ->where('is_active', true)
        ->whereDate('end_date', '>=', now())
        ->latest()
        ->get()
        ->map(function ($promo) {
            return [
                'id' => $promo->id,
                'code' => $promo->code,
                'type' => $promo->type,
                'value' => $promo->value,
                'vendor_name' => $promo->vendor ? $promo->vendor->shop_name : 'ShopHub Official',
                'vendor_logo' => $promo->vendor ? asset('storage/'.$promo->vendor->logo) : null,
                'vendor_id' => $promo->vendor ? $promo->vendor->id : null,
                'expires_at' => $promo->end_date,
            ];
        });

    return Inertia::render('Offers', [
        'promos' => $promos
    ]);
}


    public function trackOrder(Request $request)
    {
        $invoice_no = $request->input('invoice_no');
        $order = null;

        if ($invoice_no) {
            $order = Order::with(['items.product'])
                ->where('invoice_no', $invoice_no)
                ->first();
        }

        return Inertia::render('TrackOrder', [
            'order' => $order,
            'filters' => ['invoice_no' => $invoice_no]
        ]);
    }
}
