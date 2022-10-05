<?php

namespace App\Models\ServiceProvider;

use App\Models\User\UserAppointment;
use App\Models\Wallet\WalletBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ServiceProvider extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'location',
        'address',
        'specialization',
        'service_provider_category_id',
        'opening_hours',
        'image',
        'image_path',
        'staff_count',
        'email_count',
        'email_sent_count',
        'last_login',
        'onboarding_date',
        'verification_token',
        'token_used',
        'status',
    ];

    public function category(){
        return $this->belongsTo(
            ServiceProviderCategory::class,
            'service_provider_id',
            'id'
        );
    }

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
