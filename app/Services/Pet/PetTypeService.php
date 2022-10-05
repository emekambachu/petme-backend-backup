<?php

namespace App\Services\Pet;

use App\Models\Pet\PetType;

/**
 * Class PetTypeService.
 */
class PetTypeService
{
    public function petType(){
        return new PetType();
    }

    public function petTypeById($id){
        return $this->petType()->findOrFail($id);
    }

    public function createPetType($request){
        $input = $request->all();
        return $this->petType()->create($input);
    }

    public function updatePetType($request, $id){
        $input = $request->all();
        $petType = $this->petTypeById($id);
        $petType->update($input);
        return $petType;
    }


}
