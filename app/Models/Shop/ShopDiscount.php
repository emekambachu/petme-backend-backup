<?php

namespace App\Models\Shop;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'percent'
    ];

}
