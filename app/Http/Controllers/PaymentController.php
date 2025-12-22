<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');

        $order = Order::where('invoice_no', $tran_id)->first();

        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Invalid Transaction!');
        }

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        if ($order->status == 'pending') {

            $validation_url = env('SSLC_MODE') === 'sandbox'
                ? "https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php"
                : "https://securepay.sslcommerz.com/validator/api/validationserverAPI.php";

            $response = Http::withoutVerifying()->get($validation_url, [
                'val_id' => $val_id,
                'store_id' => env('SSLC_STORE_ID'),
                'store_passwd' => env('SSLC_STORE_PASSWORD'),
                'format' => 'json'
            ]);

            $result = json_decode($response->body());

            if ($result->status === 'VALID' || $result->status === 'VALIDATED') {
                $order->update([
                    'payment_status' => 'Paid',
                    'status' => 'processing',
                    'transaction_id' => $val_id,
                ]);

                return to_route('order.success', ['invoice_no' => $order->invoice_no]);
            } else {
                return redirect()->route('cart.index')->with('error', 'Payment Validation Failed!');
            }
        }

        return to_route('order.success', ['invoice_no' => $order->invoice_no]);
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order = Order::where('invoice_no', $tran_id)->first();

        if ($order && $order->user_id) {
            Auth::loginUsingId($order->user_id);
            $order->update(['status' => 'cancelled']);
        }

        return redirect()->route('cart.index')->with('error', 'Payment Failed!');
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order = Order::where('invoice_no', $tran_id)->first();
        if ($order && $order->user_id) {
            Auth::loginUsingId($order->user_id);
            $order->update(['status' => 'cancelled']);
        }

        return redirect()->route('cart.index')->with('error', 'Payment Cancelled!');
    }
}
