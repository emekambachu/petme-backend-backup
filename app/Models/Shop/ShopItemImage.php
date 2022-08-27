<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_item_id',
        'image',
    ];

    public function shop_item(){
        return $this->belongsTo(ShopItem::class, 'shop_item_id', 'id');
    }
}
