<?php

namespace App\Services\Pet;

use App\Models\Pet\Pet;
use App\Models\Pet\PetDewormDetail;
use App\Models\Pet\PetDietDetail;
use App\Models\Pet\PetVaccinationDetail;
use App\Services\Base\CrudService;

/**
 * Class PetService.
 */
class PetService
{
    private $imagePath = 'photos/pets';

    protected $crud;
    public function __construct(CrudService $crud){
        $this->crud = $crud;
    }

    public function pet(): Pet
    {
        return new Pet();
    }

    public function petById($id){
        return $this->pet()->findOrFail($id);
    }

    public function petWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->pet()->with('user', 'pet_type', 'pet_deworm_details', 'pet_diet_details', 'pet_vaccination_details');
    }

    public function petsByOwnerId($userId){
        return $this->petWithRelations()->where('user_id', $userId);
    }

    public function createPetByOwner($request, $userId){
        $input = $request->all();
        $input['user_id'] = $userId;
        $image = $this->crud->compressAndUploadImage($request, $this->imagePath, 200, 200);
        if($image){
            $input['image'] = $image;
            $input['image_path'] = @config('app.url').$this->imagePath.'/';
        }
        return $this->pet()->create($input);
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

    public function deletePet($petId, $userId){
        $this->pet()->where([
            ['id', $petId],
            ['user_id', $userId],
        ])->delete();
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
