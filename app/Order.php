<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function mertchant()
    {
        return $this->belongsTo(User::class, 'merchant_id', 'id')->with(['address', 'extraInfo', 'requisite']);
    }

    public function orderer()
    {
        return $this->belongsTo(User::class, 'user_id')->with(['address']);
    }

    public function orderList()
    {
        return $this->hasMany(OrderList::class, 'order_id');
    }

    public function deliveryTable()
    {
        return $this->hasMany(DeliveryPriceTable::class, 'seller_id', 'merchant_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id')->with(['a_district','region']);
    }

}
