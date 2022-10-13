<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model {

	protected $guarded = array();
	public $timestamps = false;

	public function campaigns() {
		return $this->hasMany('App\Models\Campaigns')->where('status','active')->where('finalized','0');
	}
}
