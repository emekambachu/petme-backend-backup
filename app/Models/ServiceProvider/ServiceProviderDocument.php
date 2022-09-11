<?php

namespace App\Models\ServiceProvider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderDocument extends Model
{
    use HasFactory;
    protected $fillable = [
      'name',
      'document',
      'status',
      'approved_by'
    ];

    public function service_provider(){
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id', 'id');
    }
}
