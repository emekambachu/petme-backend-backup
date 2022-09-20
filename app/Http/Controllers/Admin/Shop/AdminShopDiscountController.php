<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shop\AdminShopDiscountRequest;
use App\Http\Requests\Admin\Shop\AdminUpdateShopDiscountRequest;
use App\Services\Shop\ShopDiscountService;
use Illuminate\Http\Request;

class AdminShopDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private $discount;
    public function __construct(ShopDiscountService $discount){
        $this->discount = $discount;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $discounts = $this->discount->shopDiscount()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'discounts' => $discounts,
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
    public function store(AdminShopDiscountRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $discount = $this->discount->shopDiscount()
                ->create($request->all());
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $discount = $this->discount->shopDiscountById($id);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AdminUpdateShopDiscountRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $discount = $this->discount->shopDiscountById($id);
            $discount->update($request->all());
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

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->discount->shopDiscountById($id)->delete();
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
