<?php

namespace App\Models\Pet;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetDietDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pet_id',
        'food_name',
        'day',
        'date',
    ];

    public function pet(){
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
