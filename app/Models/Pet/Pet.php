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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PetType::class, 'pet_type_id', 'id');
    }

    public function deworm_details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PetDewormDetail::class, 'pet_id', 'id');
    }

    public function diet_details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PetDietDetail::class, 'pet_id', 'id');
    }

    public function vaccination_details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PetVaccinationDetail::class, 'pet_id', 'id');
    }
}
