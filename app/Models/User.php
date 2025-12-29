<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'phone',
        'address',
        'profile_photo_path',
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    // ==========================================
    // 🖼️ Accessors (Virtual Attributes)
    // ==========================================

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {

        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
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

    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
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
