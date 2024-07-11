<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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
}
