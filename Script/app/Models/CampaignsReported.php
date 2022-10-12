<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignsReported extends Model {

	protected $guarded = array();
	public $timestamps = false;
	public $table = 'campaigns_reported';
	
	public function user(){
		return $this->belongsTo('App\Models\User')->first();
	}
	
	public function campaigns() {
        return $this->belongsTo('App\Models\Campaigns')->first();
    }
}