<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $guarded = [];

    public const NEW_INVOICE = 1;
    public const PAYED_INVOICE = 2;
}