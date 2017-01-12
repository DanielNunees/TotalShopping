<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //ps_order_detail
    public $timestamps = false;
    protected $table = 'ps_order_detail';
    protected $primaryKey = 'id_order_detail';

    public static function OrderDetail($orderDetail){
    	return OrderDetail::insert($orderDetail);
    }

    public static function getOrderDetail($order_id){
    	if(is_array($order_id)){
    		return OrderDetail::whereIn('id_order',$order_id)->select('product_id','product_attribute_id','product_quantity','id_order')>groupBy('id_order')->get();
    	}
    	return OrderDetail::where('id_order',$order_id)->select('product_id','product_attribute_id','product_quantity','id_order','id_order_detail')->get();
    }
}
