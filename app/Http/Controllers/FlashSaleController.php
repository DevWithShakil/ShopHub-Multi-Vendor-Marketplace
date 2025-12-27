<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    /**
     * Display the specified flash sale with all products.
     */
    public function show($id)
    {
        $flashSale = FlashSale::with(['products' => function ($query) {
                $query->where('is_active', true)
                      ->withPivot('discount_price', 'stock_limit', 'sold');
            }])
            ->where('id', $id)
            ->where('status', true)
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->firstOrFail();

        // ২. Inertia return view
        return Inertia::render('FlashSale/Details', [
            'flashSale' => $flashSale
        ]);
    }
}
