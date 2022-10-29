<?php

namespace App\Http\Controllers\User\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Pet\Diet\UserStorePetDietRequest;
use App\Http\Requests\User\Pet\Diet\UserUpdatePetDietRequest;
use App\Services\Pet\PetDietService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPetDietController extends Controller
{
    protected PetDietService $diet;
    public function __construct(PetDietService $diet){
        $this->diet = $diet;
    }

    public function index($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->diet->dietByPetId($id, Auth::user()->id)
                ->latest()->get();
            return response()->json([
                'success' => true,
                'diets' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(UserStorePetDietRequest $request, $petId): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->diet->addDietDetail($request, $petId, Auth::user()->id);
            return response()->json([
                'success' => true,
                'diet' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(UserUpdatePetDietRequest $request, $petId): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->diet->updateDietDetail($request, $petId, Auth::user()->id);
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message'] ?? null,
                'diet' => $data['diet'] ?? null
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
            $data = $this->diet->deleteDietDetail($id, Auth::user()->id);
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
