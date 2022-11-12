<?php

namespace App\Services\Pet;

use App\Models\Pet\Pet;
use App\Models\Pet\PetDewormDetail;
use App\Models\Pet\PetDietDetail;
use App\Models\Pet\PetVaccinationDetail;
use App\Services\Base\BaseService;
use App\Services\Base\CrudService;
use App\Services\User\UserService;

/**
 * Class PetService.
 */
class PetService
{
    private $imagePath = 'photos/pets';

    protected $crud;
    protected $user;
    public function __construct(CrudService $crud, UserService $user){
        $this->crud = $crud;
        $this->user = $user;
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
        return $this->pet()->with('user', 'type', 'deworm_details', 'diet_details', 'vaccination_details');
    }

    public function petsByOwnerId($userId): \Illuminate\Database\Eloquent\Builder
    {
        return $this->petWithRelations()->where('user_id', $userId);
    }

    public function createPetByOwner($request, $userId){
        $input = $request->all();
        $input['user_id'] = $userId;
        $image = $this->crud->compressAndUploadImage($request, $this->imagePath, 200, 200);
        if($image){
            $input['image'] = $image;
            $input['image_path'] = BaseService::$baseUrl.$this->imagePath.'/';
        }
        return $this->pet()->create($input);
    }

    public function publishPet($userId, $petId): array
    {
        $user = $this->user->userById($userId);
        if($user){
            $pet = $this->crud->publishItem($this->petById($petId));
        }else{
            $pet = [
                'item' => null,
                'message' => 'user does not exists',
            ];
        }
        return $pet;
    }

    public function updatePet($request, $userId, $petId): array
    {
        $user = $this->user->userById($userId);
        if($user){
            $pet = $this->petById($petId);
            $input = $request->all();
            $image = $this->crud->compressAndUploadImage($request, $this->imagePath, 200, 200);
            if($image){
                $input['image'] = $image;
                $input['image_path'] = @config('app.url').$this->imagePath.'/';
            }
            $pet->update($input);
            $pet = [
                'pet' => $pet,
                'message' => $pet->name.' updated',
            ];
        }else{
            $pet = [
                'pet' => null,
                'message' => 'user does not exists',
            ];
        }
        return $pet;
    }

    public function deletePet($userId, $petId): void
    {
        $pet = $this->petById($petId);
        $this->crud->deleteFile($pet->image, $this->imagePath);
        $this->pet()->where([
            ['id', $petId],
            ['user_id', $userId],
        ])->delete();
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
