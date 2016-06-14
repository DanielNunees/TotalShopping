<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\ProductLang;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductImages;

class ProductHomeController extends Controller
{
    public function index(){
    	$products = ProductLang::where('id_lang','2')->select('name','id_product')->get();
    	foreach ($products as $product) {
    		$product->ProductPrice; //Price of product
    		$product->ProductStock; //Return the quantity available in stock and null, otherwise
    		
            if(isset($product->ProductImage->id_image)){
        		$idImage = (string) $product->ProductImage->id_image; //Retriving the cover image id,
    		    $image = '/img/p/';									  //Set a path,create a new
    		    for ($i = 0; $i < strlen($idImage); $i++) {			  //variable 'image',insert in 
    		      $image .= $idImage[$i] . '/';						  //$products array and return
    		    }
    		    $image .= $idImage . '.jpg';
    		    $product->ProductImage->image= $image;
            }   
    	}
    	return $products;
    }
}