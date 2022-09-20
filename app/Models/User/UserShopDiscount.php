<?php

namespace App\Models\User;

use App\Models\Shop\ShopDiscount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShopDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'shop_discount_id',
    ];

    public function shop_discount(){
        return $this->belongsTo(ShopDiscount::class, 'shop_discount_id', 'id');
    }
}
