<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class HistoricController extends Controller
{
    //
    public function getHistoric(){
    	$id_customer = myAuthController::getAuthenticatedUser();
    	$order = OrderController::getOrderByCustomerId($id_customer);
    	
    	foreach ($order as $key => $value) {
    		//$order_ids[] = ;
    		$i =0;
    		$product = OrderController::getOrderDetails($value->id_order);

    		if(count($product)>0){
    			$result[] = ProductController::retrivingProduct($product[$i]->product_id);
    			$i++;
    		}
    	}

    	return $result;
    	

    	foreach ($products as $key => $value) {
    		//s$key = array_search(40489, array_column($userdb, 'uid'));
    		return $value;
    		
    		
    	}

    	return $result;


    }
}
