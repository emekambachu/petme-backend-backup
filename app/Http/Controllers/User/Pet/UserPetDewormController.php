<?php

namespace App\Http\Controllers\User\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Pet\Deworm\UserStorePetDewormRequest;
use App\Http\Requests\User\Pet\Deworm\UserUpdatePetDewormRequest;
use App\Services\Pet\PetDewormService;
use Illuminate\Support\Facades\Auth;

class UserPetDewormController extends Controller
{
    protected PetDewormService $deworm;
    public function __construct(PetDewormService $deworm){
        $this->deworm = $deworm;
    }

    public function index($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->deworm->dewormsByPetId($id, Auth::user()->id)
                ->latest()->get();
            return response()->json([
                'success' => true,
                'deworms' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(UserStorePetDewormRequest $request, $petId): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->deworm->addDewormDetail($request, $petId, Auth::user()->id);
            return response()->json([
                'success' => true,
                'deworm' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(UserUpdatePetDewormRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->deworm->updateDewormDetail($request, $id, Auth::user()->id);
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message'] ?? null,
                'deworm' => $data['deworm'] ?? null
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
            $data = $this->deworm->deleteDewormDetail($id, Auth::user()->id);
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
