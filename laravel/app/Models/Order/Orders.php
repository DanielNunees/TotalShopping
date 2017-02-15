<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //ps_orders
    public $timestamps = false;
	protected $table = 'ps_orders';
	protected $primaryKey = 'id_order';

	public static function getOrderByCartId($id_cart){
		return Orders::where('id_cart',$id_cart)->get();
	}

	public static function getOrderIdByCustomerId($id_customer){
		return Orders::where('id_customer',$id_customer)->select('reference','id_order')->get();
	}

}
