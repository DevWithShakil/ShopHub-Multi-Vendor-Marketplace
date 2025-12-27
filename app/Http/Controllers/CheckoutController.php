<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
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
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'payment_method' => 'required|in:cod,sslcommerz',
            'items' => 'required|array|min:1',
            'total_price' => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {

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

                    if (isset($item['variant'])) {
                        $variant = ProductVariant::find($item['variant']['id']);
                        if ($variant) {
                            $variant->decrement('stock_qty', $item['quantity']);
                        }
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'vendor_id' => $product->vendor_id,
                        'variant_id' => $item['variant']['id'] ?? null,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

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
                        'product_name' => 'Computer Accessories',
                        'product_category' => 'Electronics',
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

                return response()->json([
                    'invoice_no' => $order->invoice_no,
                    'message' => 'Order placed successfully'
                ]);
            });

        } catch (\Exception $e) {
            return response()->json(['error' => 'Order Failed! ' . $e->getMessage()], 500);
        }
    }

    public function success($invoice_no)
    {
        return Inertia::render('OrderSuccess', [
            'invoice_no' => $invoice_no
        ]);
    }

}
