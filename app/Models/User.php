<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    // Filament Access Control Logic
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

public function vendor()
{
    return $this->hasOne(Vendor::class);
}

public function wishlist()
{
    return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'product_id');
}

    // Helpers
    public function isAdmin() { return $this->role === 'admin'; }
    public function isVendor() { return $this->role === 'vendor'; }
    public function isCustomer() { return $this->role === 'customer'; }
}
