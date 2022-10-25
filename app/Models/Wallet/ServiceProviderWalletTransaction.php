<?php

namespace App\Models\Wallet;

use App\Models\ServiceProvider\ServiceProviderModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderWalletTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_provider_wallet_id',
        'service_provider_id',
        'debit',
        'credit',
    ];

    public function service_provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'service_provider_id', 'id');
    }

    public function service_provider_wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderWallet::class,
            'service_provider_wallet_id', 'id');
    }
}
