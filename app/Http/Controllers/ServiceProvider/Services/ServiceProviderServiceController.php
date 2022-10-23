<?php

namespace App\Http\Controllers\ServiceProvider\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceProvider\Service\StoreServiceProviderServiceRequest;
use App\Services\ServiceProvider\ServiceProviderServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceProviderServiceController extends Controller
{
    protected $service;
    public function __construct(ServiceProviderServiceService $service){
        $this->service = $service;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->service->servicesByProviderid(Auth::user()->id)
                ->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'services' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(StoreServiceProviderServiceRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->service->createService($request, Auth::user()->id);
            return response()->json([
                'success' => true,
                'service' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(StoreServiceProviderServiceRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->service->updateService($request, $id, Auth::user()->id);
            return response()->json([
                'success' => true,
                'service' => $data,
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
            $data = $this->service->deleteService($id, Auth::user()->id);
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
