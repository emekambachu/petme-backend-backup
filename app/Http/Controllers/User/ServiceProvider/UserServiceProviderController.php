<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Services\ServiceProvider\ServiceProviderCategoryService;
use App\Services\ServiceProvider\ServiceProviderService;
use Illuminate\Http\Request;

class UserServiceProviderController extends Controller
{
    protected ServiceProviderService $provider;
    protected ServiceProviderCategoryService $category;
    public function __construct(
        ServiceProviderService $provider,
        ServiceProviderCategoryService $category
    ){
        $this->provider = $provider;
        $this->category = $category;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->provider->approvedServiceProviders()->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'total' => $data->total(),
                'service_providers' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function categories(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->category->serviceProviderCategory()->orderBy('name')->paginate(12);
            return response()->json([
                'success' => true,
                'total' => $data->total(),
                'categories' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function category($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->category->serviceProviderCategoryById($id);
            return response()->json([
                'success' => true,
                'category' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
