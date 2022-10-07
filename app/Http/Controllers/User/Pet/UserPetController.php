<?php

namespace App\Http\Controllers\User\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Pet\UserStorePetRequest;
use App\Services\Pet\PetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPetController extends Controller
{
    protected $pet;
    public function __construct(PetService $pet){
        $this->pet = $pet;
    }

    public function index(){
        try {
            $pets = $this->pet->petsByOwnerId(Auth::user()->id)->latest()->get();
            return response()->json([
                'success' => true,
                'pets' => $pets
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(UserStorePetRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $pet = $this->pet->createPetByOwner($request, Auth::user()->id);
            return response()->json([
                'success' => true,
                'pet' => $pet
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}
