<?php

namespace App\Models\User;

use App\Models\ServiceProvider\ServiceProviderModel;
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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->where($this->user_type, 'user');
    }

    public function service_provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'user_id', 'id')
            ->where($this->user_type, 'service-provider');
    }
}
