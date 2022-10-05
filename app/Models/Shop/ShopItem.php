<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'shop_metric_id',
        'shop_category_id',
        'cost',
        'status',
        'owner',
    ];

    public function discount(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ShopItemDiscount::class, 'shop_item_id', 'id');
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShopItemImage::class, 'shop_item_id', 'id');
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShopOrder::class, 'shop_item_id', 'id');
    }

    public function metric(){
        return $this->belongsTo(ShopMetric::class, 'shop_metric_id', 'id');
    }

    public function category(){
        return $this->belongsTo(ShopCategory::class, 'shop_category_id', 'id');
    }

}
