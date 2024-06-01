<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Officer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'officer';

    protected $fillable = [
        'avatar',
        'name',
        'phone',
        'email',
        'bday',
        'pin',
    ];

    protected $hidden = [
        'pin',
    ];
}
