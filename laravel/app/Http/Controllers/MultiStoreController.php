<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Products\Product;
use App\Http\Controllers\ProductController;
use App\Models\Shop;


class MultiStoreController extends Controller
{
	public function getStores(){
		return Shop::getIdStores();
	}

    public function getProducts($id_store,$page){
    	$id_product_max = Product::getCountProductsFromStore($id_store);
        $id_product = Product::getProductsFromStore($id_store)->pluck('id_product')->values();
        $id_product = $id_product->chunk(10);

        if($page>count($id_product))
            return response()->json(['alert' => 'All Products Are Load'], 400);
        
        $aux = ProductController::retrivingProduct($id_product[$page-1]->values()->toArray());
        return array('products'=>$aux,'max'=>$id_product_max);
    }   
}


