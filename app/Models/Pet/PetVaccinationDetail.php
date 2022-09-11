<?php

namespace App\Models\Pet;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetVaccinationDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pet_id',
        'drug',
        'source',
        'administer_rate',
        'frequency',
        'administration_duration',
        'batch_number',
        'last_session',
        'next_session',
        'created_by',
        'location',
    ];

    public function pet(){
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
