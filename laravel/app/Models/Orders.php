<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //ps_orders
    public $timestamps = false;
	protected $table = 'ps_orders';
	protected $primaryKey = 'id_order';

	public static function getOrder($id_cart){
		return Orders::where('id_cart',$id_cart)->get();
	}
}
