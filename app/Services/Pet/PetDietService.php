<?php

namespace App\Services\Pet;

use App\Models\Pet\PetDietDetail;

/**
 * Class PetDietService.
 */
class PetDietService
{
    public function petDiet(): PetDietDetail
    {
        return new PetDietDetail();
    }

    public function dietById($id){
        return $this->petDiet()->findOrFail($id);
    }

    public function dietByPetId($id){
        return $this->petDiet()->where('pet_id', $id);
    }

    public function addDietDetail($request, $petId, $ownerId){
        $input = $request->all();
        $input['pet_id'] = $petId;
        $input['user_id'] = $ownerId;

        return $this->petDiet()->create($input);
    }

    public function updateDietDetail($request, $id, $ownerId): array
    {
        $input = $request->all();
        $diet = $this->dietById($id);
        if($ownerId !== $diet->user_id){
            return [
                'success' => false,
                'message' => 'incorrect user'
            ];
        }
        $diet->update($input);
        return [
            'success' => true,
            'message' => 'Updated',
            'diet' => $diet
        ];
    }

    public function deleteDietDetail($id, $ownerId): array
    {
        $diet = $this->dietById($id);
        if($ownerId !== $diet->user_id){
            return [
                'success' => false,
                'message' => 'incorrect user'
            ];
        }
        $diet->delete();
        return [
            'success' => true,
            'message' => 'Deleted'
        ];
    }
}
