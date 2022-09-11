<?php

namespace App\Models\Wallet;

use App\Models\ServiceProvider\ServiceProvider;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletBalance extends Model
{
    use HasFactory;
    protected $fillable = [
      'user_id',
      'service_provider_id',
      'amount'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function service_provider(){
        return $this->belongsTo(ServiceProvider::class, 'service_provider_id', 'id');
    }
}
