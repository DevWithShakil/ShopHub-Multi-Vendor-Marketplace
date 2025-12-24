<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['category', 'vendor'])
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->where('is_active', true);

        if ($request->has('category') && $request->category != null) {
            // Check for category using JSON query for translations or slug
            $category = Category::where('slug', $request->category)->first();

            if ($category) {
                $allCategoryIds = $this->getAllCategoryIds($category);
                $query->whereIn('category_id', $allCategoryIds);
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('base_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('base_price', 'desc');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categoryName' => $request->category ?? 'All Products',
            'filters' => $request->only(['search', 'min_price', 'max_price', 'sort', 'category']),
        ]);
    }

    public function show($slug)
    {
        // ১. মেইন প্রোডাক্ট ফেচ করা
        $product = Product::with([
                'category',
                'vendor',
                'variants',
                'reviews' => function($query) {
                    $query->with('user:id,name')->latest();
                }
            ])
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // ২. 🔥 Related/Similar Products লজিক
        // একই ক্যাটাগরির প্রোডাক্ট, কিন্তু বর্তমানটি বাদে
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // বর্তমান প্রোডাক্ট বাদ
            ->where('is_active', true)
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->inRandomOrder() // র‍্যান্ডমলি সাজানো (যাতে প্রতিবার আলাদা আসে)
            ->take(4) // সর্বোচ্চ ৪টি প্রোডাক্ট
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'base_price' => $p->base_price,
                    'discount_price' => $p->discount_price,
                    'thumb_image' => $p->thumb_image,
                    'reviews_avg_rating' => $p->reviews_avg_rating,
                    'reviews_count' => $p->reviews_count,
                ];
            });

        // ৩. ডাটা রেসপন্স সাজানো
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
            'category' => $product->category ? ['name' => $product->category->name, 'slug' => $product->category->slug] : null,
            'vendor' => $product->vendor ? ['shop_name' => $product->vendor->shop_name] : null,
            'variants' => $product->variants,
            'vendor_id' => $product->vendor_id,

            'reviews' => $product->reviews,
            'reviews_avg_rating' => $product->reviews_avg_rating,
            'reviews_count' => $product->reviews_count,

            // ✅ রিলেটেড প্রোডাক্ট পাঠানো হলো
            'related_products' => $relatedProducts,
        ];

        return Inertia::render('ProductDetails', [
            'product' => $productData
        ]);
    }

    /**
     * ✅ Helper Function: Get All Child Category IDs Recursively
     */
    private function getAllCategoryIds($category)
    {
        $ids = collect([$category->id]);

        $category->load('children');

        foreach ($category->children as $child) {
            $ids = $ids->merge($this->getAllCategoryIds($child));
        }

        return $ids->unique()->toArray();
    }
}
