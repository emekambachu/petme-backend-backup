<?php

namespace App\Http\Controllers\Home\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ShopItemCollection;
use App\Http\Resources\Shop\ShopItemResource;
use App\Services\Shop\ShopService;
use Illuminate\Http\Request;

class HomeShopController extends Controller
{
    private $shop;
    public function __construct(ShopService $shop){
        $this->shop = $shop;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $items = $this->shop->shopItemPublished()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'shop_items' => new ShopItemCollection($items),
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
            if($item){
                return response()->json([
                    'success' => true,
                    'shop_item' => new ShopItemResource($item),
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Item does not exist',
            ]);


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $shopItems = $this->shop->searchShopItems($request, $this->shop->shopItemPublishedJoins());
            return response()->json([
                'success' => true,
                'shop_items' => $shopItems['shop_items'],
                'total' => $shopItems['total'],
                'search_values' => $shopItems['search_values'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}
