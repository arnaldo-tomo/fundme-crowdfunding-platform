<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Model;

class Languages extends Model {

	protected $guarded = array();
	public $timestamps = false;

	protected $fillable = [ 'name', 'abbreviation' ];
}
