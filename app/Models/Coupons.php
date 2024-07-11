<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'discount_value',
        'minimum_cart',
        'expired_at',
        'limit_usage',
        'current_usage',
        'status',
        'discount_threshold',
        'max_discount_value',
    ];
}
