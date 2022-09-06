<?php

namespace App\Services\Shop;

use App\Models\Shop\ShopCategory;
use App\Models\Shop\ShopMetric;

/**
 * Class ShopSettingsService.
 */
class ShopSettingsService
{
    public function shopCategory(): ShopCategory
    {
        return new ShopCategory();
    }

    public function shopMetric(): ShopMetric
    {
        return new ShopMetric();
    }

    public function createShopCategory($request){
        $input = $request->all();
        return $this->shopCategory()->create($input);
    }

    public function updateShopCategory($request, $id){
        return $this->shopCategory()->findOrFail($id)
            ->update($request->all());
    }

    public function deleteShopCategory($id): void
    {
        $this->shopCategory()->findOrFail($id)->delete();
    }

    public function createShopMetric($request){
        return $this->shopMetric()->create($request->all());
    }

    public function updateShopMetric($request, $id){
        return $this->shopMetric()->findOrFail($id)
            ->update($request->all());
    }

    public function deleteShopMetric($id): void
    {
        $this->shopMetric()->findOrFail($id)->delete();
    }
}
