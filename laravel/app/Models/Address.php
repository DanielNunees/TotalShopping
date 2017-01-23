<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $timestamps = false;
    protected $table = 'ps_address';
    protected $primaryKey = 'id_state';
    protected $fillable = ['address1','address2','postcode','city','phone','phone_mobile','firstname','lastname','id_state'                  ,'id_customer','id_country','date_add','date_upd'];

    public function loadStates(){
    	return $this->hasMany('App\Models\State','id_state')->select('id_state','name');
    }

    static public function getIdAdressFromCustomer($id_customer){
    	return Address::select('id_address')->where('id_customer',$id_customer)->get();
    }
    public static function getAddress($id_customer){
    	return Address::where('id_customer',$id_customer)->where('active','1')->select('id_state','lastname','firstname','address1','address2','postcode','city','phone','phone_mobile','other')->get();
	}
    public static function createAddress($address){
        return Address::insertGetId($address);
    }

    public static function updateOrCreateAddress($id_customer,$address){
        return Address::updateOrCreate(['id_customer' => $id_customer],$address);
    }

}
