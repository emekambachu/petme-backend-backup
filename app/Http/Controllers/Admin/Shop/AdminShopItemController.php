<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\AdminStoreShopItemRequest;
use App\Services\Shop\ShopService;
use Illuminate\Http\Request;

class AdminShopItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private $shop;
    public function __construct(ShopService $shop){
        $this->shop = $shop;
    }

    public function index()
    {
        try {
            $items = $this->shop->shopItemWithRelations()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'items' => $items,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function publish($id): \Illuminate\Http\JsonResponse
    {
        try {
            $item = $this->shop->publishShopItem($id);
            return response()->json([
                'success' => true,
                'shop_item' => $item['shop_item'],
                'message' => $item['message'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdminStoreShopItemRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $shopItem = $this->shop->storeShopItem($request);
            return response()->json([
                'success' => true,
                'shop_item' => $shopItem,
                'shop_item_images' => $shopItem->shop_item_images ?? null,
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
            $shopItems = $this->shop->searchShopItems($request);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $shopItem = $this->shop->shopItemWithRelations()->findOrFail($id);
            return response()->json([
                'success' => true,
                'shop_item' => $shopItem,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $shopItem = $this->shop->updateShopItem($request, $id);
            return response()->json([
                'success' => true,
                'shop_item' => $shopItem,
                'shop_item_images' => $shopItem->shop_item_images ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->shop->deleteShopItem($id);
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

    public function deleteShopItemImage(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->shop->deleteShopItemImage($id);
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
