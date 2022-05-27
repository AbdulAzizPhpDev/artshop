<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone_number', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function extraInfo()
    {
        return $this->belongsTo(ExtraInfoUser::class, 'id', 'seller_id');
    }

    public function requisite()
    {
        return $this->belongsTo(Requisite::class, 'id', 'seller_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'id', 'user_id')->with(['a_district', 'region']);
    }

    public function addressInfo()
    {
        return $this->belongsTo(Address::class, 'id', 'user_id')->where('address_type', true)->with(['a_district', 'region']);
    }

    public function paymentSystem()
    {
        return $this->belongsTo(PaymentSystemTool::class, 'id', 'seller_id');

    }


}
