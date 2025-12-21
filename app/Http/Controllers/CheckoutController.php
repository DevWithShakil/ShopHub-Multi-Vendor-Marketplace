<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'items' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id() ?? null,
                'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
                'total_amount' => $request->total_price + 120,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'address_details' =>[
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city
                ],
            ]);

            foreach ($request->items as $item) {

                if (isset($item['variant'])) {
                    $variant = ProductVariant::find($item['variant']['id']);
                    if ($variant && $variant->stock_qty >= $item['quantity']) {
                        $variant->decrement('stock_qty', $item['quantity']);
                    }
                } else {
                    $product = Product::find($item['id']);
                    // $product->decrement('stock', $item['quantity']);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'vendor_id' => Product::find($item['id'])->vendor_id,
                    'variant_id' => $item['variant']['id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

        if ($request->payment_method === 'bkash' || $request->payment_method === 'online') {

            $post_data = [
                'store_id' => env('SSLC_STORE_ID'),
                'store_passwd' => env('SSLC_STORE_PASSWORD'),
                'total_amount' => $order->total_amount,
                'currency' => 'BDT',
                'tran_id' => $order->invoice_no,
                'success_url' => route('payment.success'),
                'fail_url' => route('payment.fail'),
                'cancel_url' => route('payment.cancel'),
                'cus_name' => $order->address_details['name'],
                'cus_email' => auth()->user()->email ?? 'guest@customer.com',
                'cus_add1' => $order->address_details['address'],
                'cus_city' => $order->address_details['city'],
                'cus_country' => 'Bangladesh',
                'cus_phone' => $order->address_details['phone'],
                'shipping_method' => 'NO',
                'product_name' => 'E-commerce Items',
                'product_category' => 'General',
                'product_profile' => 'general',
            ];

            // API call (Sandbox URL)
           $response = Http::withoutVerifying()
                ->asForm()
                ->post('https://sandbox.sslcommerz.com/gwprocess/v4/api.php', $post_data);

            $ssl = json_decode($response->body());

            if (isset($ssl->status) && $ssl->status == 'SUCCESS') {

                return Inertia::location($ssl->GatewayPageURL);
            } else {
                return back()->withErrors(['error' => 'Payment Gateway Error']);
            }
        }

        return redirect()->route('order.success', $order->invoice_no);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
        }
    }

    public function success($invoice_no)
    {
        return Inertia::render('OrderSuccess', [
            'invoice_no' => $invoice_no
        ]);
    }
}
