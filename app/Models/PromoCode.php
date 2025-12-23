<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount',
        'usage_limit', 'usage_count', 'starts_at', 'expires_at', 'is_active', 'vendor_id'
    ];

    public function isValid()
    {
        if (!$this->is_active) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;

        return true;
    }

    public function vendor()
{
    return $this->belongsTo(Vendor::class);
}
}
