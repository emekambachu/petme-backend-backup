<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'shop_item_id',
        'shop_order_id',
    ];

    public function shop_item(){
        return $this->belongsTo(ShopItem::class, 'shop_item_id', 'id');
    }
    public function shop_order(){
        return $this->belongsTo(ShopOrder::class, 'shop_order_id', 'id');
    }
}
