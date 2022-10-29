<?php

namespace App\Models\ServiceProvider;

use App\Models\Appointment\Appointment;
use App\Models\Wallet\ServiceProviderWallet;
use App\Models\Wallet\WalletBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ServiceProviderModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'service_providers';
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

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            ServiceProviderCategory::class,
            'service_provider_id',
            'id'
        );
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ServiceProviderWallet::class, 'service_provider_id', 'id');
    }

    public function services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceProviderService::class, 'service_provider_id', 'id');
    }

    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class, 'service_provider_id', 'id');
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceProviderDocument::class, 'service_provider_id', 'id');
    }
}
