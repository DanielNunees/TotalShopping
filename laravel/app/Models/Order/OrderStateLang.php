<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderStateLang extends Model
{
    //ps_order_state_lang

    public $timestamps = false;
    protected $table = 'ps_order_state_lang';
    protected $primaryKey = 'id_order_state';

    public static function getOrderState($id_order_state){
    	if(is_array($id_order_state))
    		return OrderStateLang::whereIn('id_order_state',$id_order_state)->select('name','id_order_state')->where('id_lang',2)->get();
    	return OrderStateLang::where('id_order_state',$id_order_state)->select('name')->where('id_lang',2)->get();
    }
}
