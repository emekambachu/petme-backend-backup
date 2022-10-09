<?php

namespace App\Services\User;

use App\Models\User\User;

/**
 * Class UserService.
 */
class UserService
{
    protected $imagePath = 'photos/users';

    public function user(): User
    {
        return new User();
    }

    public function userWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->user()->with('appointments', 'pets', 'location', 'shop_discounts');
    }

    public function userById($id){
        return $this->userWithRelations()->findOrFail($id);
    }

    public function userByEmail($email){
        return $this->userWithRelations()->where('email', $email)->first();
    }

    public function verifyUser($id): array
    {
        $user = $this->user()->findOrFail($id);
        $message = '';
        if($user->status === 'verified'){
            $user->status = 'pending';
        }else{
            $user->verified = 'verified';
        }
        $message = $user->name.' is now '.$user->status;
        $user->save();
        return [
            'user' => $user,
            'message' => $message,
        ];
    }

}
