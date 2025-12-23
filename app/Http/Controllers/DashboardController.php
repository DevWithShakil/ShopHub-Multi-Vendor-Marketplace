<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'total_orders' => Order::where('user_id', $userId)->count(),
            'pending_orders' => Order::where('user_id', $userId)->where('status', 'pending')->count(),
        'completed_orders' => Order::where('user_id', $userId)
            ->whereIn('status', ['delivered', 'completed'])
            ->count(),

        'total_spend' => Order::where('user_id', $userId)
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('total_amount'),
            'wishlist_count' => auth()->user()->wishlist()->count(),
        ];

        $recent_orders = Order::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'recent_orders' => $recent_orders
        ]);
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10)
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'invoice_no' => $order->invoice_no,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('d M, Y h:i A'),
                    'items_count' => $order->items->count(),
                ];
            });

        return Inertia::render('Dashboard/Orders', [
            'orders' => $orders
        ]);
    }

public function orderDetails($invoice_no)
{
    $userId = Auth::id();

    $order = Order::with(['items.product'])
        ->where('invoice_no', $invoice_no)
        ->where('user_id', $userId)
        ->firstOrFail();

    $order->items->each(function ($item) use ($userId) {
        $item->setRelation('review', Review::where('order_id', $item->order_id)
            ->where('product_id', $item->product_id)
            ->where('user_id', $userId)
            ->first()
        );
    });

    return Inertia::render('Dashboard/OrderDetails', [
        'order' => $order
    ]);
}

    public function profile()
    {
        return Inertia::render('Dashboard/Profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back();
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back();
    }
}
