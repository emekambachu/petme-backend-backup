<?php

namespace App\Services\ServiceProvider;

/**
 * Class ServiceProviderServiceService.
 */
class ServiceProviderServiceService
{
    public function serviceProviderService(): \App\Models\ServiceProvider\ServiceProviderService
    {
        return new \App\Models\ServiceProvider\ServiceProviderService();
    }

    public function servicesByProviderid($id){
        return $this->serviceProviderService()->where('service_provider_id', $id);
    }

    public function serviceById($id){
        return $this->serviceProviderService()->findOrFail($id);
    }

    public function createService($request, $userId){
        $input = $request->all();
        $input['service_provider_id'] = $userId;
        return $this->serviceProviderService()->create($input);
    }

    public function updateService($request, $id, $userId){
        $input = $request->all();
        $input['service_provider_id'] = $userId;
        $service = $this->serviceById($id);
        $service->update($input);
        return $service;
    }

    public function deleteService($id, $userId): array
    {
        $service = $this->serviceById($id);
        if($userId !== $service->service_provider_id){
            return [
                'success' => false,
                'message' => 'Error occurred',
            ];
        }
        $service->delete();
        return [
            'success' => true,
            'message' => 'Deleted',
        ];
    }

}
