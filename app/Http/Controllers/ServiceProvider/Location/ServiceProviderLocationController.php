<?php

namespace App\Http\Controllers\ServiceProvider\Location;

use App\Http\Controllers\Controller;
use App\Services\User\UserLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceProviderLocationController extends Controller
{
    protected UserLocationService $location;
    public function __construct(UserLocationService $location){
        $this->location = $location;
    }

    public function currentLocation(): \Illuminate\Http\JsonResponse
    {
        try {
            $location = $this->location->getLocationFromUserId(Auth::user()->id, 'service-provider');
            return response()->json([
                'success' => true,
                'location' => $location
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateLocation(): \Illuminate\Http\JsonResponse
    {
        try {
            $location = $this->location->updateUserLocationFromIp(Auth::user()->id, 'service-provider')->get();
            return response()->json([
                'success' => true,
                'location' => $location
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
