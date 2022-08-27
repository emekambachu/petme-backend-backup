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
        'type',
        'gender',
        'registration_number',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
