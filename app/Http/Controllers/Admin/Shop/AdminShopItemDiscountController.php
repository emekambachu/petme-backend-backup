<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shop\AdminAddShopItemDiscountRequest;
use App\Services\Shop\ShopDiscountService;
use Illuminate\Http\Request;

class AdminShopItemDiscountController extends Controller
{
    private $discount;
    public function __construct(ShopDiscountService $discount){
        $this->discount = $discount;
    }

    public function show($id){
        try {
            $discount = $this->discount->shopItemDiscount()
                ->where('shop_item_id', $id)->findOrFail($id);
            return response()->json([
                'success' => true,
                'discount' => $discount,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(AdminAddShopItemDiscountRequest $request, $id){
        try {
            $discount = $this->discount->addDiscountToShopItem($request, $id);
            return response()->json([
                'success' => true,
                'discount' => $discount,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($itemId, $discountId): \Illuminate\Http\JsonResponse
    {
        try {
            $this->discount->deleteDiscountFromShopItem($itemId, $discountId);
            return response()->json([
                'success' => true,
                'message' => 'Deleted',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
