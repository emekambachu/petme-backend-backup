<?php

namespace App\Services\Pet;

use App\Models\Pet\Pet;
use App\Models\Pet\PetDewormDetail;
use App\Models\Pet\PetDietDetail;
use App\Models\Pet\PetVaccinationDetail;

/**
 * Class PetService.
 */
class PetService
{
    public function pet(): Pet
    {
        return new Pet();
    }

    public function petDeworm(): PetDewormDetail
    {
        return new PetDewormDetail();
    }

    public function petVaccination(): PetVaccinationDetail
    {
        return new PetVaccinationDetail();
    }

    public function petDiet(): PetDietDetail
    {
        return new PetDietDetail();
    }

    public function petWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->pet()->with('user', 'pet_type', 'pet_deworm_details', 'pet_diet_details', 'pet_vaccination_details');
    }

    public function deletePet($id){
        $this->pet()->findOrFail($id)->delete();
    }

    public function dewormingRecords($id){
        return $this->petDeworm()->where('pet_id', $id)
            ->latest()->get();
    }

    public function vaccinationRecords($id){
        return $this->petVaccination()->where('pet_id', $id)
            ->latest()->get();
    }

    public function dietRecords($id){
        return $this->petDiet()->where('pet_id', $id)
            ->latest()->get();
    }

}
