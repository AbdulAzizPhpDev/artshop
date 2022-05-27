<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $table = "products";

    public function category()
    {
        return $this->hasOne(Catalog::class, 'id', 'catalog_id')->with(['parent']);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'id', 'product_id');
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id')->with(['address', 'extraInfo', 'requisite', 'paymentSystem']);
    }

    public function reviews()
    {
        return $this->hasMany(CustomerRating::class, 'product_id')->with(['user'])->orderBy('created_at');
    }

    public function wishList()
    {
        return $this->hasOne(Wishlist::class, 'product_id')->where('user_id', Auth::check() ? Auth::user()->getAuthIdentifier() : null);
    }
}
