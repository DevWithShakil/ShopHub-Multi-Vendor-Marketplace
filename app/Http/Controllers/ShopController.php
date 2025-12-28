<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Start with Product Query
        $query = Product::query()
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->with(['category', 'brand', 'reviews']);

        // Search Filter
        $query->when($request->input('search'), function ($q, $search) {
            $q->where('name', 'like', "%{$search}%");
        });

        // Vendor Filter
        $query->when($request->input('vendor_id'), function ($q, $vendorId) {
            $q->where('vendor_id', $vendorId);
        });

        // Category Filter
        $query->when($request->input('category'), function ($q, $slug) {
            $q->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
        });

        // Brand Filter
        $query->when($request->input('brand'), function ($q, $slug) {
            $q->whereHas('brand', function ($query) use ($slug) {
                $query->where('slug', $slug);
            });
        });

        // Price Range Filter
        $query->when($request->input('min_price'), function ($q, $min) {
            $q->where('base_price', '>=', $min);
        });
        $query->when($request->input('max_price'), function ($q, $max) {
            $q->where('base_price', '<=', $max);
        });

        // Sorting Logic
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

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();

        return Inertia::render('Shop/Index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['search', 'category', 'brand', 'sort', 'min_price', 'max_price', 'vendor_id']),
        ]);
    }
}
