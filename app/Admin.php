<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'user_name', 'password', 'role_id',
    ];


    protected $hidden = [
        'password', 'rememberToken',
    ];
    //
}
