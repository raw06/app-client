<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'provider' ,
        'access_token',
        'expires_in',
        'refresh_token',
        'status'
    ];
}
