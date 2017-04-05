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
use App\Http\Controllers\CartController;
use App\Http\Controllers\myAuthController;
use Exception;

class CartController extends Controller
{

    protected $id_customer;

    public static function createCart(){
        $id_customer = myAuthController::getAuthenticatedUser();
        if(!is_numeric($id_customer)){
            return $id_customer;
        }
        if($id_customer==0){
            $customer_secure_key='';
            $customer_address=0;
            $id_customer=0;
            $today = date("Y-m-d H:i:s");
            $id_guest=GuestController::newGuest(0);
        }
        else{
            $customer_address = userController::getAddress($id_customer);
            if(!$customer_address){ 
                $customer_address=0;
            }
        	$customer_secure_key = User::getSecureKey($id_customer);
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
        if(!is_numeric($id_customer)){
            return $id_customer;
        }

        $cart_id = Cart::RetrivingCartId($id_customer);

        $order = OrderController::getOrderByCartId($cart_id['id_cart']);

        if($order){
            $cart_id['id_cart'] = CartController::createCart();
        }

        $product = ProductController::productQuantityInStock($request->id_product);

        if($product->isEmpty()){
            return response()->json(['product_not_found' => 'error'], 400); 
        }
            $find = false;
            foreach ($product as $key => $value) {
                if($value->id_product_attribute == $request->id_product_attribute){
                    $id_product_attribute = $request->id_product_attribute;
                    $find  = true;
                    break;
                }
            }
        if(!$find){
            return response()->json(['product_not_found' => 'error'], 400);
        }
        $address = userController::getAddress($id_customer);
        if(!$address){ 
            $address=0;
        }

        if(is_null($cart_id)){
            $cart_id['id_cart'] = CartController::createCart();

            CartController::addToCart($cart_id['id_cart'],$request,$address);
        }
        else{
            try{
            $cart_products = CartProducts::findProduct($cart_id['id_cart'], $request->id_product, $request->id_product_attribute);
            }
            catch(Exception $e){
                return $e;
            }
            //return $cart_products;
            if(count($cart_products)==0){
                //return 'if';
                CartController::addToCart($cart_id['id_cart'],$request,$address);
            }
            else{

                CartProducts::updateQuantity($cart_id['id_cart'],$request->id_product,$request->id_product_attribute,$request->product_quantity);
                return $cart_id['id_cart'];
            }
        }
        return response()->json(['success' => 'ok'], 200);
    }

    public function removeProducts(Request $request){
        //return is_null($request->data);

    	$this->validate($request, [
          'id_product' => 'bail|required|integer',
          'id_product_attribute' => 'bail|required|integer'
        ]);
        
        $id_customer = myAuthController::getAuthenticatedUser();
        if(!is_numeric($id_customer)){
            return $id_customer;
        }
        
        $cart_id = Cart::RetrivingCartId($id_customer);

        $delete_product_cart = CartProducts::removeProduct($cart_id['id_cart'],$request->id_product,$request->id_product_attribute);
        return response()->json(['success' => 'ok'], 200);
    }

    public static function loadCart(){
        $id_customer = myAuthController::getAuthenticatedUser();
        if(!is_numeric($id_customer)){
            return $id_customer;
        }

        $cart_id = Cart::RetrivingCartId($id_customer);

        $order = OrderController::getOrderByCartId($cart_id['id_cart']);
        if($order){ 
            throw new Exception("Have Some Order With This Cart Id", 500);
        }

        $products = CartProducts::allProductsFromCart($cart_id['id_cart']);
        
        if($products->isEmpty()) 
            return $products;
        
        $id_product_attribute = $products->pluck('id_product_attribute')->toArray();
        $id_product = $products->pluck('id_product')->toArray();
        
        $final = ProductController::retrivingProduct($id_product,$id_product_attribute); 

        $final = $final->toArray();
        $aux = array_column($final, 'attributes');
        $result = array_reduce($aux, 'array_merge', array());

        $count = count($final[0]['attributes']);
        $a=0;
        foreach ($result as $key => $value) {
            $aux = $final;
            $aux[$a]['attributes'] = $value;
            $needle = array_search($value['id_product_attribute'],$id_product_attribute);
            $aux[$a]['quantity'] = $products[$needle]->quantity;
            $new[] = $aux[$a];
            
            $count--;
            if($count == 0){
                $a++;
                if(isset($final[$a]['attributes']))
                    $count = count($final[$a]['attributes']);
            }
        }

        return $new;
    }

    public function addToCart($cart_id,$request,$address){
        $product_shop_id = ProductController::productIdShop($request->id_product);
        $product_shop_id = $product_shop_id[0]['id_shop_default']; 
        $today = date("Y-m-d H:i:s");
            $insert_product = new CartProducts;
            $insert_product = $insert_product->insertGetId([
                'id_cart'=>$cart_id,
                'id_product'=>$request->id_product,
                'id_address_delivery'=>$address,
                'id_shop'=>$product_shop_id,
                'id_product_attribute'=>$request->id_product_attribute,
                'quantity'=> $request->product_quantity,
                'date_add'=>$today]);
            return $insert_product;
    }

    public static function deleteCart(){
        $id_customer = myAuthController::getAuthenticatedUser();
        if(!is_numeric($id_customer)){
            return $id_customer;
        }
        $cart_id = Cart::RetrivingCartId($id_customer);

        Cart::DeleteCart($cart_id['id_cart']);
        return response()->json(['success' => 'ok'], 200);

    }

    public static function removeAllProducts(){
        $id_customer = myAuthController::getAuthenticatedUser();
        if(!is_numeric($id_customer)){
            return $id_customer;
        }
        $cart_id = Cart::RetrivingCartId($id_customer);

        CartProducts::removeAllProducts($cart_id['id_cart']);
        return response()->json(['success' => 'ok'], 200);
    }
} 