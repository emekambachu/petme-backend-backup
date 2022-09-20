<?php

namespace App\Services\Shop;

use App\Models\Shop\ShopDiscount;

/**
 * Class ShopDiscountService.
 */
class ShopDiscountService
{
    public function shopDiscount(): ShopDiscount
    {
        return new ShopDiscount();
    }

    public function shopDiscountById($id){
        return $this->shopDiscount()->findOrFail($id);
    }
}
