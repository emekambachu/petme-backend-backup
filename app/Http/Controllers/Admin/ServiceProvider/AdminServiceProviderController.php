<?php

namespace App\Http\Controllers\Admin\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceProvider\AdminStoreServiceProviderRequest;
use App\Http\Requests\Admin\ServiceProvider\AdminUpdateServiceProviderRequest;
use App\Services\ServiceProvider\ServiceProviderService;
use Illuminate\Http\Request;

class AdminServiceProviderController extends Controller
{
    private $provider;
    public function __construct(ServiceProviderService $provider){
        $this->provider = $provider;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $providers = $this->provider->serviceProviderWithRelations()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'service_providers' => $providers,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function documents($id): \Illuminate\Http\JsonResponse
    {
        try {
            $documents = $this->provider->serviceProviderDocuments($id)
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'service_provider_documents' => $documents,
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
            $data = $this->provider->publishServiceProvider($id);
            return response()->json([
                'success' => true,
                'message' => $data['message'],
                'service_provider' => $data['item'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(AdminStoreServiceProviderRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $provider = $this->provider->storeServiceProvider($request);
            return response()->json([
                'success' => true,
                'service_provider' => $provider,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function storeDocument(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $document = $this->provider->storeServiceProviderDocument($id, $request);
            return response()->json([
                'success' => true,
                'service_provider_document' => $document,
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
            $providers = $this->provider->searchServiceProviders($request);
            return response()->json([
                'success' => true,
                'service_providers' => $providers['providers'],
                'total' => $providers['total'],
                'search_values' => $providers['search_values'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $provider = $this->provider->serviceProviderWithRelations()->findOrFail($id);
            return response()->json([
                'success' => true,
                'service_provider' => $provider,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(AdminUpdateServiceProviderRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $provider = $this->provider->updateServiceProviders($request, $id);
            return response()->json([
                'success' => true,
                'service_provider' => $provider,
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
            $this->provider->deleteServiceProvider($id);
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

    public function deleteDocument(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->provider->deleteServiceProviderDocument($id);
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
