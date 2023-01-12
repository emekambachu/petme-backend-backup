<?php

namespace App\Models\Chat;

use App\Models\ServiceProvider\ServiceProviderModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConnection extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'service_id',
        'name',
    ];

    public function senderUserConnection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiverUserConnection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function senderProviderConnection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'sender_id', 'id');
    }

    public function receiverProviderConnection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'receiver_id', 'id');
    }

    public function conversations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChatConversation::class, 'chat_connection_id', 'id');
    }
}
