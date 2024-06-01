<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'user_id',
        'service_id',
        'service_fee',
        'transport_fee',
        'total',
        'payment_method',
        'officer_id',
        'status',
        'date',
        'latitude',
        'longitude',
        'rating',
        'cancelled_reason',
        'customer_notes',
        'is_emergency',
        'emergency_image',
        'created_at',
        'updated_at',
    ];
}
