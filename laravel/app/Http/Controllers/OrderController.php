<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order\Orders;
use App\User;
use App\Http\Requests;
use App\Models\Address;
use App\Tools\Tools;
use App\Models\Cart;
use App\Models\Order\OrderDetail;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderHystoryController;

class OrderController extends Controller
{
    public static function createOrder($reference){
        $id_customer = myAuthController::getAuthenticatedUser();
        
        $cart_products = CartController::loadCart();
        $price = 0;
        $values = [];
        $shop_ids = [];

        foreach ($cart_products as $key => $value) {
            if(!isset($values[$value->id_shop]))
                $values[$value->id_shop] = 0;
            $products[$value->id_shop][] = $value;
            $quantity = $value->quantity;
            number_format($value->product['description'][0]['product_price']['price'],2);
            $price = $values[$value->id_shop] + number_format($quantity *$value->product['description'][0]['product_price']['price'],2, '.', '');
            $values[$value->id_shop] = $price;
            if(!in_array($value->id_shop, $shop_ids))
                $shop_ids[] = $value->id_shop;
        }

    	$today = date("Y-m-d H:i:s");

    	$customer_secure_key = User::getSecureKey($id_customer);
    	$customer_address = Address::getIdAdressFromCustomer($id_customer);
        $id_cart = $cart_id = Cart::RetrivingCartId($id_customer);
        
        $id_cart = $id_cart['id_cart']; 	
        
        foreach($shop_ids as $key => $value1){
            $order_id = new Orders;
        	$order_id = $order_id->insertGetId(
                ['reference' => $reference,
        		'id_shop'=>$value1,'id_shop_group'=>1,'id_carrier'=>3,
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

        		'total_paid'=>$values[$value1],
        		'total_paid_tax_incl'=>$values[$value1],
        		'total_paid_tax_excl'=>$values[$value1],
        		'total_paid_real'=>0,
        		'total_products'=>$values[$value1],
        		'total_products_wt'=>$values[$value1],

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
            
            OrderController::OrderDetail($order_id,$products[$value1]);
            OrderHystoryController::newHistory($order_id);
        }
        
        CartController::createCart();
        
        return $order_id;

    }

    public static function OrderDetail($order_id,$products){

        foreach($products as $key => $value){
                $order_details[] = [
                    'id_order' => $order_id ,
                    'id_order_invoice'=>0,'id_warehouse'=>0,'id_shop'=>$key,
                    'product_id'=>$value['id_product'] ,
                    'product_attribute_id'=>$value['id_product_attribute'],
                    'product_name'=>$value['product']['description'][0]['name'],
                    'product_quantity'=>$value['quantity'],
                    'product_quantity_in_stock'=>1,
                    'product_quantity_refunded'=>0,
                    'product_quantity_return'=>0,
                    'product_quantity_reinjected'=>0,
                    'product_price'=>$value['product']['description'][0]['product_price']['price'],
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
                    'total_price_tax_incl'=>$value['product']['description'][0]['product_price']['price'],
                    'total_price_tax_excl'=>$value['product']['description'][0]['product_price']['price'],
                    'unit_price_tax_incl'=>$value['product']['description'][0]['product_price']['price'],
                    'unit_price_tax_excl'=>$value['product']['description'][0]['product_price']['price'],
                    'total_shipping_price_tax_incl'=>0,
                    'total_shipping_price_tax_excl'=>0,
                    'purchase_supplier_price'=>0,
                    'original_product_price'=> $value['product']['description'][0]['product_price']['price'],
                    'original_wholesale_price'=>$value['product']['description'][0]['product_price']['price']];

                    ProductController::productUpdateStock(
                        $value['id_product'],
                        $value['id_product_attribute'],
                        $value['quantity']
                    );
            }
            OrderDetail::OrderDetail($order_details);
    }

    public static function getOrderByCartId($cart_id){
        return Orders::getOrderByCartId($cart_id);
    }

    public static function getOrderByCustomerId($id_customer){
        return Orders::getOrderIdByCustomerId($id_customer);
    }

    public static function getOrderDetails($id_order){
        return OrderDetail::getOrderDetail($id_order);
    }
}
