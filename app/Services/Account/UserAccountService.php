<?php

namespace App\Services\Account;

use App\Models\User\User;

/**
 * Class UserAccountService.
 */
class UserAccountService
{
    public static function user(){
        return new User();
    }

    public static function userWithRelations(){
        return self::user()->with('user_appointments', 'pets');
    }
}
