<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Tools;
use App\Http\Requests;

class HistoricController extends Controller
{
    //
    public function getHistoric(){
    	
        $id_customer = myAuthController::getAuthenticatedUser();
        if(!is_numeric($id_customer)){
            return $id_customer;
        } 

    	$orders = OrderController::getOrderByCustomerId($id_customer); //get order_id from user, 
        if(!$orders){                                                  //false if have no orders
            return;
        }
        $order_id = $orders->pluck('id_order')->toArray();//transform to array to use whereIn query
        
        $state = OrderController::getOrderState($orders->pluck('current_state')->toArray()); //current state order

        $order_details = OrderController::getOrderDetails($order_id);
        $order_details_id = $order_details->pluck('product_id')->unique()->values()->toArray();
        $product_attribute_id = $order_details->pluck('product_attribute_id')->values()->toArray();
        $products = ProductController::retrivingProduct($order_details_id,$product_attribute_id)->toArray();
        $a=0;

        $result=[];
        $keys=[];
        foreach ($products as $key => $product) {
            $novo = (array_column($product['attributes'],'id_product_attribute'));
            $result = array_merge($result,$novo);
            $flip[] = array_flip($novo);
            $a = count($product['attributes']);
            while ($a) {
                $keys[] = $key;
                $a--;
            }
        }
        $flat = call_user_func_array('array_merge', $flip);
        $keys = array_combine($result, $keys);
        $attribute = array_combine($result, $flat);
        foreach ($product_attribute_id as $key => $id) {
            $aux = $products[$keys[$id]]['attributes'];
            $products[$keys[$id]]['attributes'] = $products[$keys[$id]]['attributes'][$attribute[$id]];
            $aux2[$id] = $products[$keys[$id]];
            //print_r($aux2[$id]);
            $resume = $order_details->where('product_attribute_id',$id)->values();
            $resume[0]->product = $aux2[$id];
            $resume[0]->product_attribute_id = 00;
            $products[$keys[$id]]['attributes'] = $aux;
        }
        $order_details = $order_details->toArray();
        //return $order_details;
        foreach ($order_details as $key => $value) {
            
            $order = $orders->where('id_order',$value['id_order'])->values();
            $bbb[$value['id_order']][] = $value;

            $aux = $state->where('id_order_state',$order[0]->current_state)->values();

            $order[0]->products = $bbb[$value['id_order']];
            
            $order[0]->state = $aux[0]['name'];
        }

        return $orders;
    }
}//
