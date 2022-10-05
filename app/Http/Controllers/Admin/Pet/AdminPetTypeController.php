<?php

namespace App\Http\Controllers\Admin\Pet;

use App\Http\Controllers\Controller;
use App\Services\Pet\PetTypeService;
use Illuminate\Http\Request;

class AdminPetTypeController extends Controller
{
    private $petType;
    public function __construct(PetTypeService $petType){
        $this->petType = $petType;
    }

    public function index(){
        try {
            $petTypes = $this->petType->petType()->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'pet_types' => $petTypes,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request){
        try {
            $petType = $this->petType->createPetType($request);
            return response()->json([
                'success' => true,
                'pet_type' => $petType,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id){
        try {
            $petType = $this->petType->updatePetType($request, $id);
            return response()->json([
                'success' => true,
                'pet_type' => $petType,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id){
        try {
            $this->petType->petTypeById($id)->delete();
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
