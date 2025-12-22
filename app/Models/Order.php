<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'invoice_no',
        'total_amount',
    /**
     * Get the order items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
        'payment_method',
        'payment_status',
        'status',
        'transaction_id',
        'name',
        'phone',
        'address',
        'city',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
