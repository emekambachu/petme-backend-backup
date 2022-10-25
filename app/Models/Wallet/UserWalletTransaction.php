<?php

namespace App\Models\Wallet;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWalletTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_wallet_id',
        'user_id',
        'debit',
        'credit'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user_wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserWallet::class, 'user_wallet_id', 'id');
    }
}
