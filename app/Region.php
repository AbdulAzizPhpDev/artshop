<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = "regions";

    public function district()
    {
        return $this->hasMany(Region::class, 'parent_id', 'id')->orderBy('name_uz');
    }

    public function priceList()
    {
        return $this->belongsTo(DeliveryPriceTable::class, 'id', 'region_id')->where('seller_id', auth()->user()->id);
    }

}
