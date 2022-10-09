<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_type',
        'ip',
        'country_name',
        'country_code',
        'city_name',
        'zip_code',
        'latitude',
        'longitude',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
