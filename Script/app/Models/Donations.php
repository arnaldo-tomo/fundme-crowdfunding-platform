<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donations extends Model {

	protected $guarded = array();
	public $timestamps = false;

	public function campaigns() {
        return $this->belongsTo('App\Models\Campaigns')->first();
    }

		public function rewards() {
	        return $this->belongsTo('App\Models\Rewards', 'rewards_id')->first();
	    }
}
