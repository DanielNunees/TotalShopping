<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public $timestamps = false;
    protected $table = 'ps_state';
    protected $primaryKey = 'id_state';

     public static function getIsoCode($id_state){
     	return State::where('id_state',$id_state)->select('iso_code')->get();
     }
}
