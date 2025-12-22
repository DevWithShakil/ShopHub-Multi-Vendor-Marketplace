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
            ->where('is_active', true);

        if ($request->has('category') && $request->category != null) {

            // Check for category using JSON query for translations
            $category = Category::whereRaw("name->>'en' = ?", [$request->category])
                        ->orWhereRaw("name->>'bn' = ?", [$request->category])
                        ->first();

            if ($category) {
                $allCategoryIds = $this->getAllCategoryIds($category);
                $query->whereIn('category_id', $allCategoryIds);
            }
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
                case 'newest':
                    $query->latest();
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

    private function getAllCategoryIds($category)
    {
        $ids = collect([$category->id]);

        $category->children->each(function ($child) use ($ids) {
            $ids->push($child->id);
            $child->children->each(function ($grandChild) use ($ids) {
                $ids->push($grandChild->id);
            });
        });

        return $ids->unique()->toArray();
    }
}
