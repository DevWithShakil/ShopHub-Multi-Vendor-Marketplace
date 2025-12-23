<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * ✅ Apply Coupon Logic
     */
 public function applyCoupon(Request $request)
{
    $request->validate([
        'code' => 'required|string',
        'cart_items' => 'required|array'
    ]);

    $coupon = PromoCode::where('code', $request->code)->first();

    if (!$coupon || !$coupon->isValid()) {
        return back()->withErrors(['code' => 'Invalid or expired promo code.']);
    }

    $cartItems = collect($request->cart_items);
    $applicableTotal = 0;

    if ($coupon->vendor_id) {

        $vendorItems = $cartItems->filter(function ($item) use ($coupon) {
            return isset($item['vendor_id']) && $item['vendor_id'] == $coupon->vendor_id;
        });

        if ($vendorItems->isEmpty()) {
            return back()->withErrors(['code' => 'This coupon is valid only for ' . $coupon->vendor->shop_name . ' products.']);
        }

        $applicableTotal = $vendorItems->sum(fn($item) => $item['price'] * $item['quantity']);

    } else {
        $applicableTotal = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    if ($coupon->min_order_amount && $applicableTotal < $coupon->min_order_amount) {
        return back()->withErrors(['code' => 'Minimum spend of ৳' . $coupon->min_order_amount . ' required for this coupon.']);
    }

    $discount = 0;
    if ($coupon->type === 'fixed') {
        $discount = $coupon->value;
    } else {
        $discount = ($applicableTotal * $coupon->value) / 100;
    }

    if ($discount > $applicableTotal) {
        $discount = $applicableTotal;
    }

    session()->put('coupon', [
        'code' => $coupon->code,
        'discount' => round($discount, 2),
        'vendor_id' => $coupon->vendor_id
    ]);

    return back()->with('success', 'Coupon applied successfully!');
}

    /**
     * Remove Coupon Logic
     */
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed successfully.');
    }
}
