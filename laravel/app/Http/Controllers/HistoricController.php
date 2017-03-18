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

    	$orders = OrderController::getOrderByCustomerId($id_customer); //get order_id from user
        $order_id = $orders->pluck('id_order')->toArray();             //transform to array to use whereIn query
        
        $state = OrderController::getOrderState($orders->pluck('current_state')->toArray());

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


        /* Versão Antiga, Solução com multiplos acessos ao BD*/
        /*
        foreach ($product_attribute_id as $key1 => $id) {
            foreach ($products as $key => $product) {
                if(isset($products[$key]['attributes'])){
                    //print_r($products[$key]['a']);
                    //echo ' valor procurado: '.$id;
                    //print_r(array_column($product['attributes'],'id_product_attribute'));
                    //print_r(array_intersect(array_column($product['attributes'],'id_product_attribute'),$product_attribute_id));
                    $indice = array_search($id,array_column($product['attributes'],'id_product_attribute'));
                    echo $a++.' ';
                    if(is_int($indice)){
                        $product['attributes'] = $product['attributes'][$indice];
                        $aux2[$id] = $product;
                        //print_r($products[$key]['attributes'][$indice]=0);
                        $products =$products;
                        $resume = $order_details->where('product_attribute_id',$id)->values();
                        $resume[0]->product = $aux2[$id];
                        $resume[0]->product_attribute_id = 00;
                        //array_splice($products[$key]['attributes'],-(count($products[$key]['attributes'])-$indice),1);
                        if(count($products[$key]['attributes'])===0){
                            //echo "apagado ";
                            unset($products[$key]['attributes']);
                        }
                        
                        break;
                    }
                }
                
            }
        }
        */
        /*
        foreach ($products as $key => $product) {
            print_r(array_column($product['attributes'],'id_product_attribute'));
            print_r($product_attribute_id);
            foreach ($product_attribute_id as $key => $value) {
                $indice = array_search($value,array_column($product['attributes'],'id_product_attribute'));
                echo ' Indice: '.$indice;
                echo ' Valor buscado:'.$value.' ';
                //echo $a++;
                if(is_int($indice)){
                    //print_r($product_attribute_id);
                    //echo 'value:'.$value.' ';
                    array_splice($product_attribute_id,-(count($product_attribute_id)-$key),1);
                    $aux = $product['attributes'];
                    $product['attributes'] = $product['attributes'][$indice];
                    $aux2[$value] = $product;
                    $product['attributes'] = $aux;
                    $resume = $order_details->where('product_attribute_id',$value)->values();
                    $resume[0]->product_attribute_id = $aux2[$value];
                    //$resume[0]->product = $aux2[$value];
                    //return $product_attribute_id;
                    

                    //  break;

                }
                
            }
        }
        */
        /*
        $final = [];
        if(!is_array($order))
            response()->json(['alert' => 'is_empty'], 200);
    	foreach ($order as $key => $value1) {

        		$product = OrderController::getOrderDetails($value1->id_order);
        		if(count($product)>0){
    				foreach ($product as $key1 => $value) {                                
    					$product2 = ProductController::retrivingProduct($value->product_id);
    		            sort($product2['attributes']);
    		            $attribute = array_search($value->product_attribute_id, array_column($product2['attributes'],'id_product_attribute'));
    		            $product2['attributes'] = array_slice($product2['attributes'], -(count($product2['attributes'])-$attribute),1);
                        $product[$key1]->product = $product2;
                          //echo($product2    );
                        $final[$value1->reference][] = $product[0];
                        //echo "id_order ".$value1->id_order."<br/>";
                        //echo $product;
    		    	}
    		    }
    	}
    	return ($final);
        */
    }
}//
