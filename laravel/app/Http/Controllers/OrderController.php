<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\User;
use App\Http\Requests;
use App\Models\Address;
use App\Tools\Tools;
use App\Models\Cart;
use App\Models\OrderDetail;
use App\Http\Controllers\ProductController;

class OrderController extends Controller
{
    public static function createOrder($ordemData,$id_customer){
        $price = 0;
        $count = count($ordemData['items']);
        for($i=0;$i<$count;$i++){
            if(!is_numeric($ordemData['items'][0]['_quantity'])){
                return response()->json(['error' => 'quantity is not a integer'], 500);
            }
            $product = ProductController::retrivingProduct(11);
           
           //return gettype($product);
           if(is_array($product)){
            return 'is array';
           }

           return 'ok';


            $quantity = $ordemData['items'][$i]['_quantity'];
            
            if(isset($product['description'][0]['product_price']['price'])){
                $price  = $quantity*$product['description'][0]['product_price']['price'];
            }else if(true){
                return $product;
            }

            
            //$product = json_decode($product);
            //$product = ($product->status());
            //if(($product->getContent()))
                //return 'aquasdaosdmaçlsdmaçsldmaçsd';
            return $product;
            
            //return $coisa['description'][0]['product_price']['price'];      
        }
        return 'ok';
        $reference = Tools::passwdGen(8,'NO_NUMERIC'); //TODO
    	$today = date("Y-m-d H:i:s");
    	
    	
    	$customer_secure_key = 
    	User::select("secure_key")->where('id_customer',$id_customer)->get();
    	
    	$customer_address = Address::select('id_address')->where('id_customer',$id_customer)->get();
    	
        $id_cart = Cart::select('id_cart')->where('id_customer',$id_customer)->orderBy('date_add','dsc')->first();
        $id_cart = $id_cart['id_cart'];

        $price = number_format($price,2);
        //return $price;
    	$order_id = new Orders;

    	$order_id->insertGetId(['reference' => $reference,
    		'id_shop'=>1,'id_shop_group'=>1,'id_carrier'=>3,
    		'id_lang'=>2,'id_customer'=>$id_customer,
    		'id_cart'=>$id_cart,
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
        //OrderController::OrderDetail($order_id,$ordemData);
        return $order_id;
    }

    /*public static function OrderDetail($order_id,$ordemData){
    
        $count = count($ordemData['items']);
            for($i=0;$i<$count;$i++){
                $order_details = new OrderDetail;
                
                $order_details->insertGetId([
                    'id_order' =>$order_id ,
                    'id_order_invoice'=>0,'id_order_warehouse'=>0,'id_shop'=>1,
                    'product_id'=>$ordemData['items'][$i]['_id'],
                    'product_attribute_id'=>$ordemData['items'][$i][_data]['_product_attributte'],
                    'product_name'=>$ordemData['items'][$i]['_name'],
                    'product_quantity'=>$ordemData['items'][$i]['_quantity'],
                    'product_quantity_in_stock'=>1,
                    'product_quantity_refunded'=>0,
                    'product_quantiry_return'=>0,
                    'product_quantity_reinjected'=>0,
                    'product_price'=>$ordemData['items'][$i]['_price'],
                    'reduction_percent'=>0,
                    'reduction_amount'=>0,
                    'reduction_amount_tax_incl'=>0,
                    'reduction_amount_tax_excl'=>0,
                    'group_reduction'=>0,
                    'product_quantity_discount'=>0,
                    'product_ean13'=>,
                    'product_upc'=>,
                    'product_reference'=>,
                    'product_supplier_reference'=>'',
                    'product_weight'=>0,
                    'id_taxt_rules_group'=>0,
                    'tax_computation_method'=>0,
                    'tax_name'=>'',
                    'tax_rate'=>0,
                    'ecotax'=>0,
                    'ecotax_tax_rate'=>0,
                    'discount_quantity_applied'=>,
                    'download_hash'=>0,
                    'download_nb'=>0,
                    'download_deadline'=>0,
                    'total_price_tax_incl'=>$ordemData['items'][$i]['_price'],
                    'total_price_tax_excl'=>$ordemData['items'][$i]['_price'],
                    'unit_price_tax_incl'=>$ordemData['items'][$i]['_price'],
                    'unit_price_tax_excl'=>$ordemData['items'][$i]['_price'],
                    'total_shipping_price_tax_incl'=>0,
                    'total_shipping_price_tax_excl'=>0,
                    'purchase_supplier_price'=>0,
                    'original_product_price'=>$ordemData['items'][$i]['_price'] ,
                    'original_wholesale_price'=>$ordemData['items'][$i]['_price']]);
            }
    }*/
}
