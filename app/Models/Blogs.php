<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
  protected $guarded = array();
  public $timestamps = false;

  public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

}
