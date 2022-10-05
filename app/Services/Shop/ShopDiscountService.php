<?php

namespace App\Services\Shop;

use App\Models\Shop\ShopDiscount;
use App\Models\Shop\ShopItemDiscount;

/**
 * Class ShopDiscountService.
 */
class ShopDiscountService
{
    public function shopDiscount(): ShopDiscount
    {
        return new ShopDiscount();
    }

    public function shopItemDiscount()
    {
        return new ShopItemDiscount();
    }

    public function addDiscountToShopItem($request, $shopItemId){
        $input = $request->all();
        $input['shop_item_id'] = $shopItemId;
        return $this->shopItemDiscount()->create($input);
    }

    public function deleteDiscountFromShopItem($itemId, $discountId){
        $this->shopItemDiscount()->where([
            ['id', $discountId],
            ['shop_item_id', $itemId],
        ])->delete();
    }

    public function shopDiscountById($id){
        return $this->shopDiscount()->findOrFail($id);
    }
}
