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
    	return CartProducts::where('id_cart',$id_cart)->select('id_product','id_product_attribute','quantity','id_shop')->get();
    }

    static public function removeProduct($id_cart,$id_product,$id_product_attribute){
        return CartProducts::where('id_cart',$id_cart)->where('id_product',$id_product)->where('id_product_attribute',$id_product_attribute)->delete();
    }

    static public function removeAllProducts($id_cart){
        return CartProducts::where('id_cart',$id_cart)->delete();
    }

    static public function updateQuantity($id_cart,$id_product,$id_product_attribute,$quantity){
        return CartProducts::where('id_cart',$id_cart)->where('id_product',$id_product)->where('id_product_attribute',$id_product_attribute)->update(['quantity'=>$quantity]);
    }

    public static function updateOrAddProduct($id_customer,$address){
        return Address::updateOrCreate(['id_customer' => $id_customer],$address);
    }

}
