<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

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
