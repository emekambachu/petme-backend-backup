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
        'owner'
    ];

    public function shop_item_images(){
        return $this->hasMany(ShopItemImage::class, 'shop_item_id', 'id');
    }

    public function shop_item_orders(){
        return $this->hasMany(ShopOrder::class, 'shop_item_id', 'id');
    }

    public function shop_metric(){
        return $this->belongsTo(ShopMetric::class, 'shop_metric_id', 'id');
    }

    public function shop_category(){
        return $this->belongsTo(ShopCategory::class, 'shop_category_id', 'id');
    }

}
