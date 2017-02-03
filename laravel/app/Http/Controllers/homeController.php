<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Products\ProductLang;
use App\Http\Controllers\ProductController;
use App\Models\Products\Product;


class homeController extends Controller
{
    public function index($page){
        //return $request->id_product;
        $id_product_max = Product::max('id_product');

            $count = $page*10;
            $count = $count+1;
            $count = $count-10;


        $limit = $count+10;
        if($count+10>$id_product_max)
            $limit = $id_product_max +1;

        for($i=$count;$i<$limit;$i++){
            try {
                $products[] = ProductController::retrivingProduct($i);
            } catch (Exception $e) {
                return $e->getMessage();
            }

            
        }
        return $products;
     
    }
}