<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model {

	protected $guarded = array();
	public $timestamps = false;

	public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

	public function withdrawals() {
        return $this->hasMany('App\Models\Withdrawals')->first();
    }

	public function likes(){
		return $this->hasMany('App\Models\Like')->where('status', '1');
	}

	public function donations(){
		return $this->hasMany('App\Models\Donations')->where('approved','1');
	}

	public function updates() {
		return $this->hasMany('App\Models\Updates');
	}

	public function category() {
	 	 return $this->belongsTo('App\Models\Categories', 'categories_id');
	 }

	 public function rewards() {
 		return $this->hasMany('App\Models\Rewards')->orderBy('amount');
 	}
}
