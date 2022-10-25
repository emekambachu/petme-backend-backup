<?php

namespace App\Models\User;

use App\Models\Appointment\Appointment;
use App\Models\Pet\Pet;
use App\Models\Shop\ShopDiscount;
use App\Models\Wallet\UserWallet;
use App\Models\Wallet\WalletBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
        'mobile',
        'address',
        'image',
        'image_path',
        'password',
        'last_login',
        'verification_token',
        'token_used',
        'status',
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
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserWallet::class, 'user_id', 'id');
    }

    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class, 'user_id', 'id');
    }

    public function pets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pet::class, 'user_id', 'id');
    }

    public function wallet_balance(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WalletBalance::class, 'user_id', 'id');
    }

    public function shop_discounts(){
        return $this->hasMany(UserShopDiscount::class, 'user_id', 'id');
    }

    public function location(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserLocation::class, 'user_id', 'id');
    }
}
