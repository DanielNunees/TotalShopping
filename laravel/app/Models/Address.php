<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $timestamps = false;
    protected $table = 'ps_address';
    protected $primaryKey = 'id_state';

    public function loadStates(){
    	return $this->hasMany('App\Models\State','id_state')->select('id_state','name');
    }
}
