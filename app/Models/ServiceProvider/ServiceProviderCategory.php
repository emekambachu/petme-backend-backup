<?php

namespace App\Models\ServiceProvider;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderCategory extends Model
{
    use HasFactory;
    protected $fillable = [
      'name'
    ];

}
