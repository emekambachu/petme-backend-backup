<?php

namespace App\Models\User;

use App\Models\Pet\Pet;
use App\Models\Shop\ShopDiscount;
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
        'avatar',
        'password',
        'last_login',
        'verification_token',
        'verified',
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

    public function user_appointments(){
        return $this->hasMany(UserAppointment::class, 'user_id', 'id');
    }

    public function pets(){
        return $this->hasMany(Pet::class, 'user_id', 'id');
    }

    public function wallet_balance(){
        return $this->hasOne(WalletBalance::class, 'user_id', 'id');
    }

    public function shop_discounts(){
        return $this->hasMany(ShopDiscount::class, 'user_id', 'id');
    }

    public function user_shop_discounts(){
        return $this->hasMany(UserShopDiscount::class, 'user_id', 'id');
    }
}
