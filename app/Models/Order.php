<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderCoupons()
    {
        return $this->hasMany(OrderCoupon::class);
    }
}
