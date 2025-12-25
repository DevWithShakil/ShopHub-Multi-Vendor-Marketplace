<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('Cart');
    }

    /**
     * ✅ Apply Coupon Logic (Updated)
     */
    public function applyCoupon(Request $request)
    {

        // DEBUG CODE (CartController.php -> applyCoupon এর শুরুতে বসান)
$debugCoupon = PromoCode::where('code', $request->code)->first();


        // ১. ভ্যালিডেশন
        $request->validate([
            'code' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer'
        ]);

        // ২. কুপন খুঁজে বের করা
        $coupon = PromoCode::where('code', $request->code)->first();

        // বেসিক ভ্যালিডেশন
        if (!$coupon) {
            return back()->withErrors(['code' => 'Invalid promo code.']);
        }

        if (!$coupon->is_active) {
            return back()->withErrors(['code' => 'This promo code is currently inactive.']);
        }

        // সময় চেক (Carbon দিয়ে)
        $now = Carbon::now();
        $startDate = Carbon::parse($coupon->start_date);
        $endDate = Carbon::parse($coupon->end_date)->endOfDay();

        if ($now->lt($startDate)) {
            return back()->withErrors(['code' => 'Promo code starts on ' . $startDate->format('d M, Y')]);
        }

        if ($now->gt($endDate)) {
            return back()->withErrors(['code' => 'Promo code has expired.']);
        }

        // ৩. Active Flash Sale IDs বের করা
        $activeFlashSaleIds = FlashSale::where('status', true)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->pluck('id')
            ->toArray();

        // ৪. Eligible Amount ক্যালকুলেশন
        $eligibleAmount = 0;
        $flashSaleItemsFound = false;
        $vendorMismatchFound = false;

        foreach ($request->items as $item) {

            // A. ভেন্ডর চেক (যদি কুপনটি ভেন্ডর স্পেসিফিক হয়)
            if ($coupon->vendor_id) {
                // ফ্রন্টএন্ড থেকে vendor_id না আসলে বা না মিললে বাদ
                if (!isset($item['vendor_id']) || (int)$item['vendor_id'] !== (int)$coupon->vendor_id) {
                    $vendorMismatchFound = true;
                    continue;
                }
            }

            // B. ফ্ল্যাশ সেল চেক
            $isFlashSaleProduct = false;
            if (!empty($activeFlashSaleIds)) {
                $isFlashSaleProduct = DB::table('flash_sale_product')
                    ->whereIn('flash_sale_id', $activeFlashSaleIds)
                    ->where('product_id', $item['id'])
                    ->exists();
            }

            if ($isFlashSaleProduct) {
                $flashSaleItemsFound = true;
                continue; // ফ্ল্যাশ সেল আইটেম হলে ডিসকাউন্ট পাবে না
            }

            // সব শর্ত পূরণ হলে অ্যামাউন্ট যোগ হবে
            $eligibleAmount += ($item['price'] * $item['quantity']);
        }

        // ৫. এরর হ্যান্ডলিং (লজিক্যাল অর্ডার অনুযায়ী)

        // কেস ১: কোনো আইটেমই এলিজিবল না
        if ($eligibleAmount == 0) {
            if ($coupon->vendor_id && $vendorMismatchFound && !$flashSaleItemsFound) {
                return back()->withErrors(['code' => 'This coupon is valid only for ' . $coupon->vendor->shop_name . ' products.']);
            }

            if ($flashSaleItemsFound) {
                return back()->withErrors(['code' => 'This coupon cannot be applied to Flash Sale products.']);
            }

            return back()->withErrors(['code' => 'No eligible products found for this discount.']);
        }

        // কেস ২: মিনিমাম অর্ডার অ্যামাউন্ট
        if ($coupon->min_order_amount && $eligibleAmount < $coupon->min_order_amount) {
            $shortage = $coupon->min_order_amount - $eligibleAmount;
            return back()->withErrors(['code' => 'Add eligible items worth ৳' . $shortage . ' more to apply this coupon.']);
        }

        // ৬. ডিসকাউন্ট ক্যালকুলেশন
        $discount = 0;
        if ($coupon->type === 'fixed') {
            $discount = $coupon->value;
            // ডিসকাউন্ট যেন টোটালের বেশি না হয়
            if ($discount > $eligibleAmount) {
                $discount = $eligibleAmount;
            }
        } else {
            // পার্সেন্টেজ ডিসকাউন্ট
            $discount = ($eligibleAmount * $coupon->value) / 100;

            // ম্যাক্সিমাম লিমিট চেক
            if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        }

        // ৭. সেশনে সেভ
        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => round($discount, 2),
            'type' => $coupon->type,
            'value' => $coupon->value,
            'vendor_id' => $coupon->vendor_id
        ]);

        // ৮. মেসেজ জেনারেশন
        $message = 'Coupon applied successfully!';

        if ($flashSaleItemsFound) {
            $message = 'Coupon applied to eligible items only (Flash Sale items excluded).';
        }

        return back()->with('success', $message);
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
