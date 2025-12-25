<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\FlashSale;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Checkout');
    }

    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'payment_method' => 'required|in:cod,sslcommerz',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {

                // 2. Create Order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'total_amount' => $validated['total_price'],
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'Unpaid',
                    'status' => 'pending',
                ]);

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['id']);

                    // --- A. Stock Management ---
                    if (isset($item['variant'])) {
                        $variant = ProductVariant::find($item['variant']['id']);
                        if (!$variant || $variant->stock_qty < $item['quantity']) {
                            throw new \Exception("Stock unavailable for variant: " . $product->name);
                        }
                        $variant->decrement('stock_qty', $item['quantity']);
                    } else {
                    }

                    // --- B. Create Order Item ---
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'vendor_id' => $product->vendor_id,
                        'variant_id' => $item['variant']['id'] ?? null,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);

                    // --- C. Flash Sale Sold Count Update ---
                    $activeFlashSale = FlashSale::where('status', true)
                        ->where('start_time', '<=', now())
                        ->where('end_time', '>=', now())
                        ->whereHas('products', function ($q) use ($product) {
                            $q->where('product_id', $product->id);
                        })
                        ->first();

                    if ($activeFlashSale) {
                        $activeFlashSale->products()->updateExistingPivot($product->id, [
                            'sold' => DB::raw("sold + {$item['quantity']}")
                        ]);
                    }
                }

                // 3. Coupon Logic
                if (Session::has('coupon')) {
                    Session::forget('coupon');
                }

                if ($validated['payment_method'] === 'sslcommerz') {
                    $post_data = [
                        'store_id' => env('SSLC_STORE_ID'),
                        'store_passwd' => env('SSLC_STORE_PASSWORD'),
                        'total_amount' => $order->total_amount,
                        'currency' => 'BDT',
                        'tran_id' => $order->invoice_no,
                        'success_url' => route('payment.success'),
                        'fail_url' => route('payment.fail'),
                        'cancel_url' => route('payment.cancel'),
                        'cus_name' => $order->name,
                        'cus_email' => Auth::user()->email ?? 'guest@shophub.com',
                        'cus_add1' => $order->address,
                        'cus_city' => $order->city,
                        'cus_country' => 'Bangladesh',
                        'cus_phone' => $order->phone,
                        'shipping_method' => 'NO',
                        'product_name' => 'Order #' . $order->invoice_no,
                        'product_category' => 'General',
                        'product_profile' => 'general',
                    ];

                    $api_url = env('SSLC_MODE') === 'sandbox'
                        ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
                        : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

                    $response = Http::withoutVerifying()->asForm()->post($api_url, $post_data);
                    $ssl = json_decode($response->body());

                    if (isset($ssl->status) && $ssl->status == 'SUCCESS') {
                        return response()->json(['url' => $ssl->GatewayPageURL]);
                    } else {
                        throw new \Exception('Payment Gateway Error: ' . ($ssl->failedreason ?? 'Unknown error'));
                    }
                }

                // 5. COD Response
                return response()->json([
                    'invoice_no' => $order->invoice_no,
                    'message' => 'Order placed successfully'
                ]);
            });

        } catch (\Exception $e) {
            return response()->json(['error' => 'Order Failed! ' . $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');

        if ($tran_id) {
            Order::where('invoice_no', $tran_id)->update(['payment_status' => 'Paid']);
        }

        return Inertia::render('OrderSuccess', [
            'invoice_no' => $tran_id ?? 'Unknown'
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'cart_total' => 'required|numeric|min:1'
        ]);

        $cartTotal = $request->cart_total;

        $coupon = PromoCode::where('code', $request->code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return back()->withErrors(['code' => 'Invalid or expired promo code.']);
        }

        if ($coupon->min_order_amount && $cartTotal < $coupon->min_order_amount) {
            return back()->withErrors(['code' => 'Minimum order amount is ৳' . $coupon->min_order_amount]);
        }
        $discount = 0;
        if ($coupon->type === 'fixed') {
            $discount = $coupon->value;
        } else {
            $discount = ($cartTotal * $coupon->value) / 100;
            if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $discount,
        ]);

        return back()->with('success', 'Coupon applied successfully!');
    }
}
