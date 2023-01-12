<?php

namespace App\Models\Chat;

use App\Models\ServiceProvider\ServiceProviderModel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'chat_connection_id',
        'service_id',
        'sender_id',
        'receiver_id',
        'chat_message',
        'seen',
    ];

    public function senderUserConversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiverUserConversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function senderProviderConversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'sender_id', 'id');
    }

    public function receiverProviderConversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceProviderModel::class, 'receiver_id', 'id');
    }

    public function connection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ChatConnection::class, 'chat_connection_id', 'id');
    }
}
