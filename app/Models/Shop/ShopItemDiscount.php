<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItemDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_item_id',
        'percent',
    ];

    public function shop_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ShopItem::class, 'shop_item_id', 'id');
    }
}
