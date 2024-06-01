<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTPRecovery extends Model
{
    use HasFactory;

    protected $table = 'otp_registrations';

    protected $fillable = [
        'phone',
        'otp',
        'created_at',
        'expires_at',
        'updated_at',
    ];
}
