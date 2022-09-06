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
}
