<?php

namespace App\Services\Pet;

use App\Models\Pet\PetDewormDetail;

/**
 * Class PetDewormService.
 */
class PetDewormService
{
    public function petDeworm(): PetDewormDetail
    {
        return new PetDewormDetail();
    }

    public function petDewormById($id){
        return $this->petDeworm()->findOrFail($id);
    }

    public function dewormsByPetId($petId, $userId){
        return $this->petDeworm()->where([
            ['pet_id', $petId],
            ['user_id', $userId],
        ]);
    }

    public function addDewormDetail($request, $petId, $ownerId){
        $input = $request->all();
        $input['pet_id'] = $petId;
        $input['user_id'] = $ownerId;

        return $this->petDeworm()->create($input);
    }

    public function updateDewormDetail($request, $id, $ownerId): array
    {
        $input = $request->all();
        $deworm = $this->petDewormById($id);
        if($ownerId !== $deworm->user_id){
            return [
              'success' => false,
              'message' => 'incorrect user'
            ];
        }
        $deworm->update($input);
        return [
            'success' => true,
            'message' => 'Updated',
            'deworm' => $deworm
        ];
    }

    public function deleteDewormDetail($id, $ownerId): array
    {
        $deworm = $this->petDewormById($id);
        if($ownerId !== $deworm->user_id){
            return [
                'success' => false,
                'message' => 'incorrect user'
            ];
        }
        $deworm->delete();
        return [
            'success' => true,
            'message' => 'Deleted'
        ];
    }

}
