<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLocationController extends Controller
{
    protected $location;
    public function __construct(UserLocationService $location){
        $this->location = $location;
    }

    public function currentLocation(): \Illuminate\Http\JsonResponse
    {
        try {
            $location = $this->location->getLocationFromUserId(Auth::user()->id);
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

    public function getLocation(): \Illuminate\Http\JsonResponse
    {
        try {
            $location = $this->location->addUserLocationFromIp(Auth::user()->id)->get();
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
            $location = $this->location->updateUserLocationFromIp(Auth::user()->id)->get();
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
