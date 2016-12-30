<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartProducts extends Model
{
    //ps_cart_product
    public $timestamps = false;
    protected $table = 'ps_cart_product';
    protected $primaryKey = 'id_cart';

    static public function findProduct($id_cart,$id_product,$id_product_attribute){
    	return CartProducts::where('id_product',$id_product)->where('id_product_attribute',$id_product_attribute)->where('id_cart',$id_cart)->get();
    }

    static public function allProductsFromCart($id_cart){
    	return CartProducts::where('id_cart',$id_cart)->select('id_product','id_product_attribute','quantity')->get();
    }

}
