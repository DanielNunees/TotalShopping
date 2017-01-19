<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Products\Product;
use App\Http\Controllers\ProductController;


class MultiStoreController extends Controller
{
    public function getProducts($id_store){
    	//$id_customer = myAuthController::getAuthenticatedUser();
    	$id_product = Product::getProductsFromStore($id_store);
    	foreach ($id_product as $key => $value) {
    		$products[] = ProductController::retrivingProduct($value['id_product']);
    	}
    	return $products;
    }
}
