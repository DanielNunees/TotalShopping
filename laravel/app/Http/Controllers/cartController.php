<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartProducts;
use App\Tools\Tools;
use App\Http\Requests;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\myAuthController;

class cartController extends Controller
{
    public static function createCart($id_customer){

        if(!is_numeric($id_customer)){
            return response()->json(['error' => 'ID is not a number'], 500);
        }
        if($id_customer==0){
            $customer_secure_key='';
            $customer_address=0;
            $id_customer=0;
            $today = date("Y-m-d H:i:s");
            $id_guest=GuestController::newGuest(0);
        }
        else{
        	$customer_secure_key = User::select("secure_key")->where('id_customer',$id_customer)->get();
        	$customer_address = Address::select('id_address')->where('id_customer',$id_customer)->get();
        	$customer_address = $customer_address[0]['id_address'];
            $customer_secure_key = $customer_secure_key[0]['secure_key'];
            $today = date("Y-m-d H:i:s");
        }

    	$new_cart = new Cart;
    	$new_cart = $new_cart->insertGetId(['id_shop_group' => 1,
    		'id_shop'=>1,'id_carrier'=>3,
    		'delivery_option'=>0,'id_lang'=>2,
    		'id_address_delivery'=>$customer_address,
    		'id_address_invoice'=>$customer_address,
    		'id_currency'=>2,
    		'id_customer'=>$id_customer,
    		'id_guest'=>($id_customer==0)? $id_guest : $id_customer,
    		'secure_key'=>$customer_secure_key,
    		'recyclable'=>0,'gift'=>0,
    		'gift_message'=>"",
    		'mobile_theme'=>0,
    		'allow_seperated_package'=>0,
    		'date_add'=>$today,
    		'date_upd'=>$today]);

    	return $new_cart;
    }

    public function addProducts(Request $request){

        $this->validate($request, [
          'id_product' => 'bail|required|integer',
          'id_product_attribute' => 'bail|required|integer',
          'product_quantity' => 'bail|required|integer',
        ]);

        $id_customer = myAuthController::getAuthenticatedUser();

        $cart_id = Cart::RetrivingCartId($id_customer);

        $address = Address::getIdAdressFromCustomer($id_customer);
        $address = $address[0]['id_address'];

        if(is_null($cart_id)){
            $cart_id['id_cart'] = cartController::createCart($id_customer);
            cartController::add($cart_id['id_cart'],$request,$address);
        }
        else{
            try{
            $cart_products = CartProducts::findProduct($cart_id['id_cart'], $request->id_product, $request->id_product_attribute);
            }
            catch(Exception $e){
                return $e;
            }
            if(count($cart_products)==0){
                cartController::add($cart_id['id_cart'],$request,$address);
            }
            else{
                CartProducts::where('id_cart',$cart_id['id_cart'])->where('id_product',$request->id_product)->where('id_product_attribute',$request->id_product_attribute)->update(['quantity'=>$request->product_quantity]);
            }
        }
        return response()->json(['success' => 'ok'], 200);
    }

    public function removeProducts(Request $request){
    	$this->validate($request, [
          'id_product' => 'bail|required|integer',
          'id_product_attribute' => 'bail|required|integer'
        ]);
        $id_customer = myAuthController::getAuthenticatedUser();
        $cart_id = Cart::RetrivingCartId($id_customer);
        $delete_product_cart = CartProducts::where('id_cart',$cart_id['id_cart'])->where('id_product',$request->id_product)->where('id_product_attribute',$request->id_product_attribute)->delete();

        //return $delete_product_cart;
        return response()->json(['success' => 'ok'], 200);
    }

    public static function loadCart(){
        $id_customer = myAuthController::getAuthenticatedUser();
        $cart_id = Cart::RetrivingCartId($id_customer);
        $products = CartProducts::allProductsFromCart($cart_id['id_cart']);
        if($products->isEmpty()) return response()->json(['error' => 'cart empty'], 500);
        foreach ($products as $value) {
            $id_product[] = $value->id_product;
            $quantity[] = $value->quantity;
            $id_product_attribute[] = $value->id_product_attribute;
        }

        $product = ProductController::retrivingProduct($id_product);
        
        $attributes = [];
        foreach ($id_product_attribute as $key => $value) {
            $attributes[] = array('quantity'=>$quantity[$key],'attributes'=>$product['attributes'][array_search($value, array_column($product['attributes'],'id_product_attribute'))]);
        }
        $product['attributes'] = $attributes;
        
        $product['images'] = Tools::unique_multidim_array($product['images'],'id_product');

        return $product;
        
    }

    public function add($cart_id,$request,$address){
        $today = date("Y-m-d H:i:s");
            $insert_product = new CartProducts;
            $insert_product = $insert_product->insertGetId([
                'id_cart'=>$cart_id,
                'id_product'=>$request->id_product,
                'id_address_delivery'=>$address,
                'id_shop'=>1,
                'id_product_attribute'=>$request->id_product_attribute,
                'quantity'=> $request->product_quantity,
                'date_add'=>$today]);
            return $insert_product;
    }

    public static function deleteCart(){
        $id_customer = myAuthController::getAuthenticatedUser();
        $cart_id = Cart::RetrivingCartId($id_customer);

        Cart::DeleteCart($cart_id['id_cart']);
        return response()->json(['success' => 'ok'], 200);

    }
} 