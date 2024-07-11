<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(Addon::class);
    }

    public function children()
    {
        return $this->hasMany(Addon::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags');
    }
}
