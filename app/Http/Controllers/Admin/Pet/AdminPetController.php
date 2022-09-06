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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $pet = $this->pet->petWithRelations()->findOrFail($id);
            return response()->json([
                'success' => true,
                'pet' => $pet,
                'pet_parent' => $pet->user->name,
                'pet_type' => $pet->pet_type->name,
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
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
