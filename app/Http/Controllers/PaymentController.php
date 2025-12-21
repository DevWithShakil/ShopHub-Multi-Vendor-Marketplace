<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {

    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $order = Order::where('invoice_no', $tran_id)->first();

        if ($order && $order->status == 'pending') {

            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);

            return redirect()->route('order.success', $order->invoice_no);
        }

        return redirect()->route('home')->with('error', 'Invalid Transaction');
    }

    public function fail(Request $request)
    {
        return redirect()->route('cart.index')->with('error', 'Payment Failed!');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('cart.index')->with('error', 'Payment Cancelled!');
    }
}
