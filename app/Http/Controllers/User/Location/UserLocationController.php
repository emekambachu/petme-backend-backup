<?php

namespace App\Http\Controllers\User\Location;

use App\Http\Controllers\Controller;
use App\Services\User\UserLocationService;
use Illuminate\Support\Facades\Auth;

class UserLocationController extends Controller
{
    protected UserLocationService $location;
    public function __construct(UserLocationService $location){
        $this->location = $location;
    }

    public function currentLocation(): \Illuminate\Http\JsonResponse
    {
        try {
            $location = $this->location->getLocationFromUserId(Auth::user()->id, 'user');
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
            $location = $this->location->updateUserLocationFromIp(Auth::user()->id, 'user')->get();
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
