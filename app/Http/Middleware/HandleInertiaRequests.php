<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // লোকালাইজেশন / ট্রান্সলেশন লোড করা
        $locale = app()->getLocale();
        $path = base_path('lang/' . $locale . '.json');

        $translations = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        return [
            ...parent::share($request),

            // ১. অথেনটিফিকেশন এবং ইউজার ডাটা
            'auth' => [
                'user' => $request->user(),
                'wishlist_ids' => $request->user() ? $request->user()->wishlist()->pluck('product_id') : [],
            ],

            // ২. কুপন ডাটা (Auth এর বাইরে, গ্লোবাল প্রপস হিসেবে)
            'coupon' => fn () => $request->session()->get('coupon'),

            // ৩. ফ্ল্যাশ মেসেজ (Success/Error টোস্ট দেখানোর জন্য)
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],

            // ৪. লোকালাইজেশন ডাটা
            'locale' => $locale,
            'translations' => $translations,
        ];
    }
}
