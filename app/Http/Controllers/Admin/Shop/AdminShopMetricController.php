<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Services\Shop\ShopSettingsService;
use Illuminate\Http\Request;

class AdminShopMetricController extends Controller
{
    private $metric;
    public function __construct(ShopSettingsService $metric){
        $this->metric = $metric;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $metrics = $this->metric->shopMetric()
                ->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'metrics' => $metrics,
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
            $metric = $this->metric->shopmetric()->findOrFail($id);
            return response()->json([
                'success' => true,
                'metric' => $metric,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $metric = $this->metric->createShopMetric($request);
            return response()->json([
                'success' => true,
                'metric' => $metric,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $metric = $this->metric->updateShopMetric($request, $id);
            return response()->json([
                'success' => true,
                'metric' => $metric,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->metric->deleteShopMetric($id);
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
