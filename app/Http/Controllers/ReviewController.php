<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $order = Order::where('id', $request->order_id)
              ->where('user_id', Auth::id())
              ->whereRaw('LOWER(status) = ?', ['delivered'])
              ->firstOrFail();

        Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'order_id' => $request->order_id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Review submitted successfully!');
    }
}
