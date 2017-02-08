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
        $id_product = Product::getProductsFromStore($id_store);

        $count = $page*10;
        $count = $count+1;
        $count = $count-10;
        $limit = $count+10;

        if($limit>$id_product_max)
            $limit = $id_product_max;

        if($count>$limit)
            return response()->json(['alert' => 'All Products Are Load'], 400);

        try {
            for($i=$count-1;$i<$limit;$i++){
            	// /echo $i;
                $products[] = ProductController::retrivingProduct($id_product[$i]['id_product']);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return array('max'=>$id_product_max,'products'=>$products);







    	
    	foreach ($id_product as $key => $value) {
    		$products[] = ProductController::retrivingProduct($value['id_product']);
    	}
    }
}


