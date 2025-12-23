<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
// use Spatie\Permission\Traits\HasRoles; // ✅ যদি Spatie ব্যবহার না করেন, এটি অফ রাখুন

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;
    // use HasRoles; // ✅ Spatie টেবিল মাইগ্রেশন না থাকলে এটি কমেন্ট করে রাখুন

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, vendor, customer
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // 🛡️ Filament Access Control Logic
    // ==========================================
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'vendor') {
            return $this->role === 'vendor';
        }

        return false;
    }

    // ==========================================
    // 🔗 Relationships
    // ==========================================

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'product_id');
    }

    // ==========================================
    // 🛠️ Helper Methods (Role Checks)
    // ==========================================

    /**
     * ✅ Fix: Custom hasRole method
     * এটি Spatie প্যাকেজ ছাড়াই রোল চেক করতে সাহায্য করবে
     */
    public function hasRole($role)
    {
        // যদি অ্যারে পাস করা হয় (যেমন: ['admin', 'vendor'])
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        // যদি সিঙ্গেল স্ট্রিং হয়
        return $this->role === $role;
    }

    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isVendor() {
        return $this->role === 'vendor';
    }

    public function isCustomer() {
        return $this->role === 'customer';
    }
}
