<?php

namespace App\Services\Account;

use App\Models\User\User;

/**
 * Class UserAccountService.
 */
class UserAccountService
{
    public function user(): User
    {
        return new User();
    }

    public function userWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->user()->with('user_appointments', 'pets');
    }

    public function verifyUser($id): array
    {
        $user = $this->user()->findOrFail($id);
        $message = '';
        if($user->verified === 1){
            $user->verified = 0;
            $message = $user->name.' is now unverified';
        }else{
            $user->verified = 1;
            $message = $user->name.' is now verified';
        }
        $user->save();
        return [
            'user' => $user,
            'message' => $message,
        ];
    }
}
