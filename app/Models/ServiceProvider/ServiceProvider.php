<?php

namespace App\Models\ServiceProvider;

use App\Models\User\UserAppointment;
use App\Models\Wallet\WalletBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvider extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'address',
        'services',
        'opening_hours',
        'photo',
        'photo_path',
        'staff_count',
        'email_count',
        'email_sent_count',
        'last_login',
        'onboarding_date',
        'status',
    ];

    public function wallet_balance(){
        return $this->hasOne(WalletBalance::class, 'service_provider_id', 'id');
    }

    public function services(){
        return $this->hasMany(ServiceProviderService::class, 'service_provider_id', 'id');
    }

    public function appointments(){
        return $this->hasMany(UserAppointment::class, 'service_provider_id', 'id');
    }

    public function documents(){
        return $this->hasMany(ServiceProviderDocument::class, 'service_provider_id', 'id');
    }
}
