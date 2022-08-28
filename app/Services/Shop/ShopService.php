<?php

namespace App\Services\Shop;

use App\Models\Shop\ShopItem;

/**
 * Class ShopService.
 */
class ShopService
{
    public static function shopItem (){
        return new ShopItem();
    }

    public static function shopItemWithRelations (){
        return self::shopItem()->with('shop_item_images', 'shop_item_orders', 'shop_metric', 'shop_category');
    }
}
