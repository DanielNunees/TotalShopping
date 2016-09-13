<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\User;
use App\Http\Requests;
use App\Models\Address;
use App\Tools\Tools;
use App\Models\Cart;

class OrderController extends Controller
{
    //
    public static function createOrder($ordemData,$id_customer){
    	
    	$reference = Tools::passwdGen(8,'NO_NUMERIC'); //TODO
    	$today = date("Y-m-d H:i:s");
    	
    	$price = 0;
    	$customer_secure_key = 
    	User::select("secure_key")->where('id_customer',$id_customer)->get();
    	
    	$customer_address = 
    	Address::select('id_address')->where('id_customer',$id_customer)->get();
    	

        $count = count($ordemData['items']);
        for($i=0;$i<$count;$i++){
                $quantity = $ordemData['items'][$i]['_quantity'];
                $price += number_format($ordemData['items'][$i]['_price'],2);
        }

        //return $price;
    	$order = new Orders;

    	$order->insertGetId(['reference' => $reference,
    		'id_shop'=>1,'id_shop_group'=>1,'id_carrier'=>3,
    		'id_lang'=>2,'id_customer'=>$id_customer,
    		'id_cart'=>Cart::select('cart_id')->where('id_customer',$request->id_customer)->orderBy('date_add','dsc')->get(),
    		'id_currency'=>2,  //1->Dolar($) 2->Real(R$)
    		'id_address_delivery'=>$customer_address[0]['id_address'],
    		'id_address_invoice'=>$customer_address[0]['id_address'],
    		'current_state'=>15,  //TODO 15->status:iniciado ps_order_state ps_order_state_lang
    		'secure_key'=>$customer_secure_key[0]['secure_key'],
    		'payment'=>'PagSeguro',
    		'conversion_rate'=>'1',
    		'module'=>'pagseguro',
    		'recyclable'=>0,'gift'=>0,
    		'gift_message'=>"",
    		'mobile_theme'=>0,
    		'shipping_number'=>'',
    		'total_discounts'=>0,
    		'total_discounts_tax_incl'=>0,
    		'total_discounts_tax_excl'=>0,

    		'total_paid'=>$price,
    		'total_paid_tax_incl'=>$price,
    		'total_paid_tax_excl'=>$price,
    		'total_paid_real'=>0,
    		'total_products'=>$price,
    		'total_products_wt'=>$price,

    		'total_shipping'=>0,//TODO
    		'total_shipping_tax_incl'=>0,//TODO
    		'total_shipping_tax_excl'=>0,//TODO
    		'carrier_tax_rate'=>0,//TODO
    		'total_wrapping'=>0,//TODO
    		'total_wrapping_tax_incl'=>0,//TODO
    		'round_mode'=>2,//TODO
    		'invoice_number'=>0,//TODO
    		'delivery_date'=>'',//TODO
    		'valid'=>0,
    		'date_add'=>$today,
    		'date_upd'=>$today]);

        return $order;


    }
}
