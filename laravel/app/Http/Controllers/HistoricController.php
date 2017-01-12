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
    	$order = OrderController::getOrderByCustomerId($id_customer);

    	foreach ($order as $key => $value1) {
    		$product = OrderController::getOrderDetails($value1->id_order);
    		if(count($product)>0){
				foreach ($product as $key => $value) {
					$product2 = ProductController::retrivingProduct($value->product_id);
	    			sort($product2['attributes']);
	    			if($value->product_attribute_id == $product2['attributes'][array_search($value->product_attribute_id, array_column($product2['attributes'],'id_product_attribute'))]['id_product_attribute']){
	    				unset($product2['attributes'][array_search($value->product_attribute_id, array_column($product2['attributes'],'id_product_attribute'))]);
	    				sort($product2['attributes']);

					}
				$product[$key]->product = $product2;
    		}
    		$final[] = $product;

    	}
    }
    return $final;

    	foreach ($order as $key => $value1) {
    		$products = OrderController::getOrderDetails($value1->id_order);
    		foreach ($products as $key => $value) {
	    		if(count($value)>0){
	    			$product = ProductController::retrivingProduct($value->product_id);
	    			sort($product['attributes']);



	    			if($value->product_attribute_id == $product['attributes'][array_search($value->product_attribute_id, array_column($product['attributes'],'id_product_attribute'))]['id_product_attribute']){

	    				unset($product['attributes'][array_search($value->product_attribute_id, array_column($product['attributes'],'id_product_attribute'))]);

	    				sort($product['attributes']);







	    				$product['quantity'] = $value->product_quantity;
	    				$product['id_order'] = $value1->id_order;
	    				$product['reference'] = $value1->reference;
	    			}
	    		}
	    	}
    	}
    	//$product = ProductController::retrivingProduct($id_product);
    	return ($product);
        
    }
}
