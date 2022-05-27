<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerRating extends Model
{
    protected $table = "customer_ratings";

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->where('is_active', true);
    }

    public function product()
    {
            return $this->hasOne(Product::class, 'id', 'product_id')->with(['category']);

    }
}
