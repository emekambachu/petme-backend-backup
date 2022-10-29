<?php

namespace App\Http\Controllers\User\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Pet\Vaccination\UserStorePetVaccinationRequest;
use App\Http\Requests\User\Pet\Vaccination\UserUpdatePetVaccinationRequest;
use App\Services\Pet\PetVaccinationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPetVaccinationController extends Controller
{
    protected PetVaccinationService $vaccination;
    public function __construct(PetVaccinationService $vaccination){
        $this->vaccination = $vaccination;
    }

    public function index($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->vaccination->vaccinationByPetId($id, Auth::user()->id)
                ->latest()->get();
            return response()->json([
                'success' => true,
                'vaccinations' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(UserStorePetVaccinationRequest $request, $petId): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->vaccination->addVaccinationDetail($request, $petId, Auth::user()->id);
            return response()->json([
                'success' => true,
                'vaccination' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(UserUpdatePetVaccinationRequest $request, $petId): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->vaccination->updateVaccinationDetail($request, $petId, Auth::user()->id);
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message'] ?? null,
                'vaccination' => $data['vaccination'] ?? null
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
            $data = $this->vaccination->deleteVaccinationDetail($id, Auth::user()->id);
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
