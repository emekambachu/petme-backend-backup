<?php

namespace App\Models\Pet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetType extends Model
{
    use HasFactory;
    protected $fillable = [
      'name'
    ];

    public function pets(){
        return $this->hasMany(Pet::class, 'pet_type_id', 'id');
    }
}
