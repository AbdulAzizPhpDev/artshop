<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Model
{
    protected $table = "wishlists";

    public function getProduct()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->with(['category'])->orderBy('name_ru');
    }
}
