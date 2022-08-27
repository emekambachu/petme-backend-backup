<?php

namespace App\Models\Shop;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'shop_item_id',
        'customer_name',
        'mobile',
        'email',
        'address',
        'payment_method',
        'quantity',
        'cost',
        'status'
    ];

    public function shop_item(){
        return $this->belongsTo(ShopItem::class, 'shop_item_id', 'id');
    }

    public function shop_order_items(){
        return $this->hasMany(ShopOrderItem::class, 'shop_order_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
