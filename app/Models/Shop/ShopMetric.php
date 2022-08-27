<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopMetric extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
}
