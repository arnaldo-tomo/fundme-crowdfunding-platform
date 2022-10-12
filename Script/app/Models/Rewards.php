<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rewards extends Model
{
  protected $guarded = array();
  public $timestamps = false;

  public function campaigns() {
        return $this->belongsTo('App\Models\Campaigns')->first();
    }
    public function donations() {
	        return $this->hasMany('App\Models\Donations')->first();
	    }
}
