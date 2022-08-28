<?php

namespace App\Services\Pet;

use App\Models\Pet\Pet;

/**
 * Class PetService.
 */
class PetService
{
    public static function pet(){
        return new Pet();
    }

    public static function petWithRelations(){
        return self::pet()->with('user', 'pet_type');
    }

}
