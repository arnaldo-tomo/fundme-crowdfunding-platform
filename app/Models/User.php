<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'email', 'avatar', 'status','role','token','confirmation_code','countries_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token) {

        $this->notify(new ResetPasswordNotification($token));
    }

	public function campaigns() {
        return $this->hasMany('App\Models\Campaigns');
    }

	public function country() {
        return $this->belongsTo('App\Models\Countries', 'countries_id')->first();
    }

    public function donationsReceived()
    {
      return $this->hasManyThrough(
        Donations::class,
        Campaigns::class,
        'user_id',
        'campaigns_id',
        'id',
        'id'
        )->whereApproved('1');
    }

}
