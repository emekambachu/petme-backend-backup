<?php

namespace App\Services\Admin;

use App\Models\Admin\Admin;

/**
 * Class AdminService.
 */
class AdminService
{
    public function admin(): Admin
    {
        return new Admin();
    }

    public function adminById($id)
    {
        return $this->admin()->findOrFail($id);
    }

}
