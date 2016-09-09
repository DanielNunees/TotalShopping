<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Orders;
use App\Model\User;
use App\Http\Requests;
use App\Models\Address;
use App\Tools\Tools;

class OrderController extends Controller
{
    //
    public function createOrder($orderData){

    	$reference = Tools::passwdGen(8,'NO_NUMERIC'); //TODO
    	$today = date("Y-m-d H:i:s");
    	
    	$customer_secure_key = 
    	User::select("secure_key")->where('id_customer',$orderData->id_customer)->get();
    	
    	$customer_address = 
    	Address::select('id_address')->where('id_customer',$orderData->id_customer)->get();
    	
    	$order = new Orders;

    	$order->insertGetId(['reference' => $reference,
    		'id_shop'=>1,'id_shop_group'=>1,'id_carrier'=>3,
    		'id_lang'=>2,'id_customer'=>$request->id_customer,
    		'id_cart'=> //TODO
    		'id_currency'=>2,  //1->Dolar($) 2->Real(R$)
    		'id_address_delivery'=>$customer_address[0]['id_address'],
    		'id_address_invoice'=>$customer_address[0]['id_address'],
    		'current_state'=>15  //TODO 15->status:iniciado ps_order_state ps_order_state_lang
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

    		'total_paid'=>//TODO,
    		'total_paid_tax_incl'=>,//TODO
    		'total_paid_tax_excl'=>,//TODO
    		'total_paid_real'=>0,//TODO
    		'total_products'=>,//TODO
    		'total_products_wt'=>,//TODO

    		'total_shipping'=>0,//TODO
    		'total_shipping_tax_incl'=>0,//TODO
    		'total_shipping_tax_excl'=>0,//TODO
    		'carrier_tax_rate'=>0,//TODO
    		'total_wrapping'=>0,//TODO
    		'total_wrapping_tax_incl'=>0,//TODO
    		'round_mode'=>2,//TODO
    		'invoice_number'=>0,//TODO
    		'delivery_date'=>,//TODO
    		'valid'=>0,
    		'date_add'=>$today,
    		'date_upd'=>$today]);


    }
}
