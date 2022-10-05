<?php

namespace App\Models\Chat;

use App\Models\ServiceProvider\ServiceProvider;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = [
      'sender_id',
      'sender_user',
      'receiver_id',
      'receiver_user',
      'message'
    ];

    public function sender_user(){
        return $this->belongsTo(User::class, 'sender_id', 'id')
            ->where('sender_user', 'user');
    }

    public function receiver_user(){
        return $this->belongsTo(User::class, 'receiver_id', 'id')
            ->where('receiver_user', 'user');
    }

    public function sender_service_provider(){
        return $this->belongsTo(ServiceProvider::class, 'sender_id', 'id')
            ->where('sender_user', 'service_provider');
    }

    public function receiver_service_provider(){
        return $this->belongsTo(ServiceProvider::class, 'receiver_id', 'id')
            ->where('receiver_user', 'service_provider');
    }
}
