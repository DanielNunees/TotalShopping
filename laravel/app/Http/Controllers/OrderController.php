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
    public static function createOrder($cart_products){
        $price = 0;
        $id_customer = myAuthController::getAuthenticatedUser();
        $count = count($cart_products['description']);
        for($i=0;$i<$count;$i++){
                $quantity = $cart_products['attributes'][$i]['quantity'];
                $price = $price + number_format($quantity * $cart_products['description'][$i]['product_price']['price'],2, '.', '');
        }
        
        $price = number_format($price,2);
        
        $reference = Tools::passwdGen(8,'NO_NUMERIC'); //TODO
    	$today = date("Y-m-d H:i:s");

    	$customer_secure_key = User::getSecureKey($id_customer);
    	
    	$customer_address = Address::getIdAdressFromCustomer($id_customer);
        $id_cart = $cart_id = Cart::RetrivingCartId($id_customer);
        $id_cart = $id_cart['id_cart'];

    	$order_id = new Orders;

    	$order_id = $order_id->insertGetId(['reference' => $reference,
    		'id_shop'=>1,'id_shop_group'=>1,'id_carrier'=>3,
    		'id_lang'=>2,'id_customer'=>$id_customer,
    		'id_cart'=>$id_cart,
    		'id_currency'=>2,  //1->Dolar($) 2->Real(R$)
    		'id_address_delivery'=>$customer_address[0]['id_address'],
    		'id_address_invoice'=>$customer_address[0]['id_address'],
    		'current_state'=>15,  //TODO 15->status:iniciado; ps_order_state ps_order_state_lang
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
        OrderController::OrderDetail($order_id,$cart_products);
        CartController::deleteCart();
        return $order_id;

    }

    public static function OrderDetail($order_id,$cart_products){
        $price = 0;
        $id_customer = myAuthController::getAuthenticatedUser();
        $count = count($cart_products['description']);
        for($i=0;$i<$count;$i++){
                $quantity = $cart_products['attributes'][$i]['quantity'];
                $price = $price + number_format($quantity * $cart_products['description'][$i]['product_price']['price'],2, '.', '');
        }
        
        $price = number_format($price,2);
                
        $customer_address = Address::getIdAdressFromCustomer($id_customer);
        $id_cart = $cart_id = Cart::RetrivingCartId($id_customer);
        $id_cart = $id_cart['id_cart'];            

        for($i=0;$i<$count;$i++){       
                $order_details[] = [
                    'id_order' =>$order_id ,
                    'id_order_invoice'=>0,'id_warehouse'=>0,'id_shop'=>1,
                    'product_id'=>$cart_products['description'][$i]['id_product'],
                    'product_attribute_id'=>$cart_products['attributes'][$i]['attributes']['id_product_attribute'],
                    'product_name'=>$cart_products['description'][$i]['name'],
                    'product_quantity'=>$cart_products['attributes'][$i]['quantity'],
                    'product_quantity_in_stock'=>1,
                    'product_quantity_refunded'=>0,
                    'product_quantity_return'=>0,
                    'product_quantity_reinjected'=>0,
                    'product_price'=>$cart_products['description'][$i]['product_price']['price'],
                    'reduction_percent'=>0,
                    'reduction_amount'=>0,
                    'reduction_amount_tax_incl'=>0,
                    'reduction_amount_tax_excl'=>0,
                    'group_reduction'=>0,
                    'product_quantity_discount'=>0,
                    'product_ean13'=>'',
                    'product_upc'=>'',
                    'product_reference'=>'',
                    'product_supplier_reference'=>'',
                    'product_weight'=>0,
                    'id_tax_rules_group'=>0,
                    'tax_computation_method'=>0,
                    'tax_name'=>'',
                    'tax_rate'=>0,
                    'ecotax'=>0,
                    'ecotax_tax_rate'=>0,
                    'discount_quantity_applied'=>0,
                    'download_hash'=>0,
                    'download_nb'=>0,
                    'download_deadline'=>0,
                    'total_price_tax_incl'=>$cart_products['description'][$i]['product_price']['price'],
                    'total_price_tax_excl'=>$cart_products['description'][$i]['product_price']['price'],
                    'unit_price_tax_incl'=>$cart_products['description'][$i]['product_price']['price'],
                    'unit_price_tax_excl'=>$cart_products['description'][$i]['product_price']['price'],
                    'total_shipping_price_tax_incl'=>0,
                    'total_shipping_price_tax_excl'=>0,
                    'purchase_supplier_price'=>0,
                    'original_product_price'=> $cart_products['description'][$i]['product_price']['price'],
                    'original_wholesale_price'=>$cart_products['description'][$i]['product_price']['price']];
            }
            OrderDetail::OrderDetail($order_details);
    }
}
