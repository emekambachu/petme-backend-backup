<?php

namespace App\Http\Controllers\Home\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ShopItemResource;
use App\Services\Shop\ShopService;

class HomeShopController extends Controller
{
    private $shop;
    public function __construct(ShopService $shop){
        $this->shop = $shop;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $items = $this->shop->shopItemWithRelations()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'shop_items' => ShopItemResource::collection($items),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $item = $this->shop->shopItemWithRelations()
                ->findOrFail($id);
            return response()->json([
                'success' => true,
                'shop_item' => new ShopItemResource($item),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}
