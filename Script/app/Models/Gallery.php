<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model {

	protected $guarded = array();
	public $timestamps = false;
	public $table = 'gallery';
}
