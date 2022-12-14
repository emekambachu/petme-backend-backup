<?php

namespace App\Http\Controllers\Admin\Pet;

use App\Http\Controllers\Controller;
use App\Services\Pet\PetService;
use Illuminate\Http\Request;

class AdminPetController extends Controller
{
    private $pet;
    public function __construct(PetService $pet){
        $this->pet = $pet;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        try {
            $pets = $this->pet->petWithRelations()
                ->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'pets' => $pets,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function search(){

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        try {
            $pet = $this->pet->petWithRelations()->findOrFail($id);
            return response()->json([
                'success' => true,
                'pet' => $pet,
                'pet_parent' => $pet->user->name ?? null,
                'pet_type' => $pet->pet_type->name ?? null,
                'pet_deworms' => $pet->pet_deworm_details,
                'pet_diets' => $pet->pet_diet_details,
                'pet_vaccinations' => $pet->pet_vaccination_details,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function showDeworming($id): \Illuminate\Http\JsonResponse
    {
        try {
            $deworming_records = $this->pet->dewormingRecords($id);
            return response()->json([
                'success' => true,
                'deworming_records' => $deworming_records
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function showVaccination($id): \Illuminate\Http\JsonResponse
    {
        try {
            $vaccination_records = $this->pet->vaccinationRecords($id);
            return response()->json([
                'success' => true,
                'vaccination_records' => $vaccination_records
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function showDiet($id): \Illuminate\Http\JsonResponse
    {
        try {
            $diet = $this->pet->dietRecords($id);
            return response()->json([
                'success' => true,
                'diet_records' => $diet
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->pet->deletePet($id);
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
