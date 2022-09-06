<?php

namespace App\Services\Pet;

use App\Models\Pet\Pet;

/**
 * Class PetService.
 */
class PetService
{
    public function pet(): Pet
    {
        return new Pet();
    }

    public function petWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->pet()->with('user', 'pet_type');
    }

    public function deletePet($id){
        $this->pet()->findOrFail($id)->delete();
    }

}
