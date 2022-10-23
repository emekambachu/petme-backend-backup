<?php

namespace App\Models\ServiceProvider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderService extends Model
{
    use HasFactory;
    protected $fillable = [
      'service_provider_id',
      'name',
      'cost'
    ];

    public function service_provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'service_provider_id', 'id');
    }
}
