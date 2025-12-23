<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'short_description',
        'description',
        'base_price',
        'discount_price',
        'stock_qty',
        'thumb_image',
        'gallery_images',
        'is_active',
        'is_featured',
        'has_variants',
        'approval_status',
    ];

    // Spatie Translatable Fields
    public $translatable = ['name', 'description', 'short_description'];

    protected $casts = [
        'gallery_images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'has_variants' => 'boolean',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'vendor_id' => 'integer',
    ];

    // ==========================================
    // 🔗 Relationships
    // ==========================================

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
