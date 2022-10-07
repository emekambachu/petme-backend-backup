<?php

namespace App\Models\Pet;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pet_type_id',
        'name',
        'distinguishing_marks',
        'image',
        'image_path',
        'gender',
        'dob',
        'registration_number',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pet_type(){
        return $this->belongsTo(PetType::class, 'pet_type_id', 'id');
    }

    public function pet_deworm_details(){
        return $this->hasMany(PetDewormDetail::class, 'pet_id', 'id');
    }

    public function pet_diet_details(){
        return $this->hasMany(PetDietDetail::class, 'pet_id', 'id');
    }

    public function pet_vaccination_details(){
        return $this->hasMany(PetVaccinationDetail::class, 'pet_id', 'id');
    }

    public function pet_service_providers(){
        return $this->hasMany(PetServiceProvider::class, 'pet_id', 'id');
    }
}
