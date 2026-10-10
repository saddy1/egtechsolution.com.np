<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Products that are one model sold in different options (RAM, storage, colour...). */
class VariantGroup extends Model
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
