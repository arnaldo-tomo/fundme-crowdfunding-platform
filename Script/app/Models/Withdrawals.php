<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawals extends Model {

	protected $guarded = array();
	public $timestamps = false;
	
	public function campaigns() {
        return $this->belongsTo('App\Models\Campaigns')->first();
    }
}