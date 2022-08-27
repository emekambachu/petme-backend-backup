<?php

namespace App\Services\Account;

use App\Models\Admin\Admin;

/**
 * Class AdminAccountService.
 */
class AdminAccountService
{
    public static function admin(){
        return new Admin();
    }

}
