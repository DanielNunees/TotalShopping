<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartProducts;

use App\Http\Requests;

class cartController extends Controller
{
    public function createCart($id_customer){
    	
    	$this->validate($request, [
	      'id_customer' => 'bail|required|max:128'
	    ]);

    	$customer_secure_key = User::select("secure_key")->where('id_customer',$id_customer)->get();
    	$customer_address = Address::select('id_address')->where('id_customer',$id_customer)->get();
    	$today = date("Y-m-d H:i:s");

    	$new_cart = new Cart;
    	$new_cart = $new_cart->insertGetId(['id_shop_group' => 1,
    		'id_shop'=>1,'id_carrier'=>3,
    		'delivery_option'=>0,'id_lang'=>2,
    		'id_address_delivery'=>$customer_address[0]['id_address'],
    		'id_address_invoice'=>$customer_address[0]['id_address'],
    		'id_currency'=>2,
    		'id_customer'=>$id_customer,
    		'id_guest'=>$id_customer,
    		'secure_key'=>$customer_secure_key[0]['secure_key'],
    		'recyclable'=>0,'gift'=>0,
    		'gift_message'=>"",
    		'mobile_theme'=>0,
    		'allow_seperated_package'=>0,
    		'date_add'=>$today,
    		'date_upd'=>$today]);

    	//$reference = openssl_random_pseudo_bytes(5);
      	//$reference = bin2hex($reference);
      	//$new_cart->reference = strtoupper($reference);
    	
    	return $new_cart;

    }

    public function addProducts(Request $request){

    	$cart_id = createCart($request->id_customer);
    	
    	$new_cart = new Cart;
    	$new_cart = $new_cart->insertGetId(['id_shop_group' => 1,
    		'id_cart'=>$cart_id,
    		'id_product'=>$request->id_product,
    		'id_address_delivery'=>$customer_address[0]['id_address'],
    		'id_shop'=>1,
    		'id_product_attribute'=>$request->id_product_attribute,
    		'quantity'=> $request->product_quantity,
    		'date_add'=>$today]);
    	return response()->json(['succes' => 'ok'], 200);
    }

    public function cartRemoveProducts(Request $request){
    	
    	$this->validate($request, [
          'id_product' => 'bail|required',
          'id_cart' => 'bail|required'
        ]);


        $delete_product_cart = CartProducts::where('id_product',$request->id_cart)->where('id_product',$request->id_product)->delete();

        return response()->json(['succes' => 'ok'], 200);
    }
} 