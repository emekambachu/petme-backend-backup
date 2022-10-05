<?php

namespace App\Http\Controllers\Home\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Services\ServiceProvider\ServiceProviderCategoryService;

class HomeServiceProviderController extends Controller
{
    private $category;
    public function __construct(ServiceProviderCategoryService $category){
        $this->category = $category;
    }

    public function getCategories(): \Illuminate\Http\JsonResponse
    {
        try {
            $categories = $this->category->serviceProviderCategory()->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'categories' => $categories,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getServices(): \Illuminate\Http\JsonResponse
    {
        try {
            $categories = $this->category->serviceProviderCategory()->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'categories' => $categories,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
