<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $guarded = [];

    public function products()
{
    return $this->belongsToMany(Product::class, 'flash_sale_product')
                ->withPivot('discount_price', 'stock_limit', 'sold')
                ->withTimestamps();
}
}
