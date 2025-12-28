<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\PromoCode; // ✅ Added Missing Import
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // 1. Start with Product Query
        $query = Product::query()
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->with(['category', 'brand', 'reviews']);

        // 2. Search Filter
        $query->when($request->input('search'), function ($q, $search) {
            $q->where('name', 'like', "%{$search}%");
        });

        // 3. Vendor Filter
        $query->when($request->input('vendor_id'), function ($q, $vendorId) {
            $q->where('vendor_id', $vendorId);
        });

        // 4. Category Filter
        $query->when($request->input('category'), function ($q, $slug) {
            $q->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
        });

        // 5. Brand Filter
        $query->when($request->input('brand'), function ($q, $slug) {
            $q->whereHas('brand', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
        });

        // 6. Price Range Filter
        $query->when($request->input('min_price'), function ($q, $min) {
            $q->where('base_price', '>=', $min);
        });
        $query->when($request->input('max_price'), function ($q, $max) {
            $q->where('base_price', '<=', $max);
        });

        // 7. Sorting Logic
        switch ($request->input('sort')) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        // 8. Fetch Data
        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();

        // 9. Shop Profile Logic
        $shopProfile = null;
        if ($request->has('vendor_id')) {
            $shopProfile = Vendor::query()
                ->where('id', $request->vendor_id)
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->first();
        }

        // 10. Return Response
        return Inertia::render('Shop/Index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['search', 'category', 'brand', 'sort', 'min_price', 'max_price', 'vendor_id']),
            'shopProfile' => $shopProfile ? [
                'id' => $shopProfile->id, // ✅ ID Added for Link
                'name' => $shopProfile->shop_name,
                'logo' => $shopProfile->logo ? asset('storage/' . $shopProfile->logo) : null,
                'banner' => $shopProfile->banner ? asset('storage/' . $shopProfile->banner) : null,
                'joined' => $shopProfile->created_at->format('M Y'),
                'rating' => $shopProfile->reviews_avg_rating ? number_format($shopProfile->reviews_avg_rating, 1) : 0,
                'total_reviews' => $shopProfile->reviews_count,
            ] : null,
        ]);
    }

    public function vendorProfile($id)
    {
        $vendor = Vendor::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

        // Vendor Products
        $products = Product::where('vendor_id', $id)
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->with(['category', 'brand', 'reviews'])
            ->latest()
            ->paginate(12);

        // Vendor Coupons
        $vouchers = PromoCode::where('vendor_id', $id)
            ->where('is_active', true)
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->get();

        return Inertia::render('Vendor/Show', [
            'vendor' => [
                'id' => $vendor->id,
                'name' => $vendor->shop_name,
                'logo' => $vendor->logo ? asset('storage/' . $vendor->logo) : null,
                'banner' => $vendor->banner ? asset('storage/' . $vendor->banner) : null,
                'joined' => $vendor->created_at->format('M Y'),
                'rating' => number_format($vendor->reviews_avg_rating, 1),
                'review_count' => $vendor->reviews_count,
                'address' => $vendor->shop_address,
            ],
            'products' => $products,
            'vouchers' => $vouchers,
        ]);
    }
}
