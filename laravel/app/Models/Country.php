<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;
    protected $table = 'ps_country';
    protected $primaryKey = 'id_country';


    public function loadStates(){
    	return $this->hasMany('App\Models\State','id_country')->select('id_state','name');
    }

}
