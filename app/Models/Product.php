<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'product_addons', 'product_id', 'addon_id');
    }

    public function productTag()
    {
        return $this->hasMany(ProductTag::class);
    }

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
