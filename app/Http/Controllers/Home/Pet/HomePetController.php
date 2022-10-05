<?php

namespace App\Http\Controllers\Home\Pet;

use App\Http\Controllers\Controller;
use App\Services\Pet\PetService;
use App\Services\Pet\PetTypeService;
use Illuminate\Http\Request;

class HomePetController extends Controller
{
    private $pet_type;
    public function __construct(PetTypeService $pet_type){
        $this->pet_type = $pet_type;
    }

    public function getPetTypes()
    {
        try {
            $pet_types = $this->pet_type->petType()
                ->orderBy('name')->latest()->paginate(12);
            return response()->json([
                'success' => true,
                'pet_types' => $pet_types,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}
