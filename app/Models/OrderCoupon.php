<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'code',
        'type',
        'discount_value',
        'discount_threshold',
        'max_discount_value',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
