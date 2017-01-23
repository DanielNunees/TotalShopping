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
        $final = [];
        if(!is_array($order))
            response()->json(['alert' => 'is_empty'], 200);

    	foreach ($order as $key => $value1) {
    		$product = OrderController::getOrderDetails($value1->id_order);
    		if(count($product)>0){
				foreach ($product as $key => $value) {
					$product2 = ProductController::retrivingProduct($value->product_id);
		            sort($product2['attributes']);
		            $attribute = array_search($value->product_attribute_id, array_column($product2['attributes'],'id_product_attribute'));
		            $product2['attributes'] = array_slice($product2['attributes'], -(count($product2['attributes'])-$attribute),1);
		            $product[$key]->product = $product2;
		    	}
		    	$final[] = $product;
    		}
    	}
    	return $final;   
    }
}
