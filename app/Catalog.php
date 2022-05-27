<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $table = "catalogs";

    public function parent()
    {
        return $this->belongsTo(Catalog::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Catalog::class, 'parent_id', 'id')->orderBy('name_uz');
    }
}
