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

    public static function addProducts(Request $request){
        //return $request->isGuest;
        if($request->id_customer==NULL){
            $cart_id['id_cart']=cartController::createCart(0); //cria um cart com id_customer=0
            $customer_address = 0;

        }else if($request->isGuest==null){//Se for uma guest com id cadastrado
        	$cart_id = Cart::select('id_cart')->where('id_guest',$request->id_customer)->orderBy('date_add','dsc')->first();
            $customer_address = 0;
        }
        else{
            $cart_id = Cart::select('id_cart')->where('id_customer',$request->id_customer)->orderBy('date_add','dsc')->first();
            $customer_address = Address::select('id_address')->where('id_customer',$request->id_customer)->get();
            $customer_address = $customer_address[0]['id_address'];
        }
        
        //apos criado o cart é buscada a tabela de produtos desse cart
        $cart_products = CartProducts::where('id_cart',$cart_id['id_cart'])->where('id_product',$request->id_product)->where('id_product_attribute',$request->id_product_attribute)->get();

        if($cart_products->isEmpty()){// se a tabela estiver vazia ou ainda não foi criada
        	$today = date("Y-m-d H:i:s");
            $insert_product = new CartProducts;
        	$insert_product = $insert_product->insertGetId([
        		'id_cart'=>$cart_id['id_cart'],
        		'id_product'=>$request->id_product,
        		'id_address_delivery'=>$customer_address,
        		'id_shop'=>1,
        		'id_product_attribute'=>$request->id_product_attribute,
        		'quantity'=> $request->product_quantity,
        		'date_add'=>$today]);
        }
        else{//se a tabela já foi criada e o produto a ser inserido já existe na tabela, apenas a quantidade dele é atualizada
            CartProducts::where('id_cart',$cart_id['id_cart'])->where('id_product',$request->id_product)->where('id_product_attribute',$request->id_product_attribute)->update(['quantity'=>$request->product_quantity]);
        }

        if($request->isGuest==null){
            $guest_id = Cart::select('id_guest')->where('id_cart',$cart_id['id_cart'])->get();
            return $guest_id;
        }
        else{
            return response()->json(['success' => 'ok'], 200);
        }


    }

    public function removeProducts(Request $request){
    	
    	$this->validate($request, [
          'id_product' => 'bail|required',
          'id_cart' => 'bail|required'
        ]);

        $cart_id = Cart::select('id_cart')->where('id_customer',$request->id_customer)->orderBy('date_add','dsc')->first();
        $delete_product_cart = CartProducts::where('id_product',$cart_id['id_cart'])->where('id_product',$request->id_product)->delete();

        return response()->json(['success' => 'ok'], 200);
    }
} 