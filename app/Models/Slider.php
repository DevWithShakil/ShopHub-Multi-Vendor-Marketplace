<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title', 'heading', 'description', 'image', 'product_id', 'bg_color', 'is_active', 'sort_order'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
