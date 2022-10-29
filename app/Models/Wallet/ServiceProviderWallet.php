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
        'user_id',
        'type',
        'amount'
    ];

    public function service_provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'user_id', 'id');
    }
}
