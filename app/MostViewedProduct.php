<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MostViewedProduct extends Model
{
    protected $table = "most_viewed_products";

    public function product()
    {
        return $this->hasOne(Product::class, 'id','product_id');
    }
}
