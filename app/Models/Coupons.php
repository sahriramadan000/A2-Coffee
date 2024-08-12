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

    protected static function boot()
    {
        parent::boot();

        // Event yang dijalankan ketika model sedang dibuat
        static::creating(function ($model) {
            // Cek ID terakhir yang ada di database
            $lastData = static::latest('id')->first();
            if ($lastData) {
                // Set ID baru berdasarkan ID terakhir + 1
                $model->id = $lastData->id + 1;
            } else {
                // Jika tidak ada record sebelumnya, set ID ke 1
                $model->id = 1;
            }
        });
    }
}
