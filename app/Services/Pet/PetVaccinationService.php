<?php

namespace App\Services\Pet;

use App\Models\Pet\PetVaccinationDetail;

/**
 * Class PetVaccinationService.
 */
class PetVaccinationService
{
    public function petVaccination(): PetVaccinationDetail
    {
        return new PetVaccinationDetail();
    }

    public function vaccinationById($id){
        return $this->petVaccination()->findOrFail($id);
    }

    public function vaccinationByPetId($id){
        return $this->petVaccination()->where('pet_id', $id);
    }

    public function addVaccinationDetail($request, $petId, $ownerId){
        $input = $request->all();
        $input['pet_id'] = $petId;
        $input['user_id'] = $ownerId;

        return $this->petVaccination()->create($input);
    }

    public function updateVaccinationDetail($request, $id, $ownerId): array
    {
        $input = $request->all();
        $vaccination = $this->vaccinationById($id);
        if($ownerId !== $vaccination->user_id){
            return [
                'success' => false,
                'message' => 'incorrect user'
            ];
        }
        $vaccination->update($input);
        return [
            'success' => true,
            'message' => 'Updated',
            'vaccination' => $vaccination
        ];
    }

    public function deleteVaccinationDetail($id, $ownerId): array
    {
        $vaccination = $this->vaccinationById($id);
        if($ownerId !== $vaccination->user_id){
            return [
                'success' => false,
                'message' => 'incorrect user'
            ];
        }
        $vaccination->delete();
        return [
            'success' => true,
            'message' => 'Deleted'
        ];
    }

}
