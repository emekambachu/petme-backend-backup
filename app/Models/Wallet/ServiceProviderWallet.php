<?php

namespace App\Models\Wallet;

use App\Models\ServiceProvider\ServiceProviderModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderWallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_provider_id',
        'amount'
    ];

    public function service_provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'service_provider_id', 'id');
    }
}
