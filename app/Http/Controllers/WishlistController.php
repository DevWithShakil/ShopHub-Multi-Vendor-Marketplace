<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function index()
    {
        $products = Auth::user()->wishlist()->latest('wishlists.created_at')->get();

        return Inertia::render('Wishlist/Index', [
            'products' => $products
        ]);
    }

    public function toggle(Request $request)
    {
        $user = Auth::user();
        $productId = $request->product_id;

        if ($user->wishlist()->where('product_id', $productId)->exists()) {
            $user->wishlist()->detach($productId);
            $message = 'Removed from wishlist';
            $type = 'removed';
        } else {
            $user->wishlist()->attach($productId);
            $message = 'Added to wishlist';
            $type = 'added';
        }

        return response()->json([
            'message' => $message,
            'type' => $type,
            'wishlist' => $user->wishlist()->pluck('product_id')
        ]);
    }
}
