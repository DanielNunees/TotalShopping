<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Http\Requests;
use App\Models\Product;
use App\Models\WishlistProducts;
 

class WishlistController extends Controller
{
    public function addProduct(Request $request){
    	
    	$this->validate($request, [
    	  'id_customer' => 'bail|required',
          'id_product' => 'bail|required',
          'id_product_attribute' => 'bail|required',
      	]);

    	$wishlist = Wishlist::select('id_wishlist')->where('id_customer',$request->id_customer)->get();
        if($wishlist->isEmpty()){
            return response()->json(['error' => 'page_not_found'], 404);
        }

    	$new_wishlist = new WishlistProducts;
    	$new_wishlist->id_wishlist = $wishlist[0]->id_wishlist;
    	$new_wishlist->id_product = $request->id_product;
    	$new_wishlist->id_product_attribute = $request->id_product_attribute;
    	$new_wishlist->quantity = 1;
    	$new_wishlist->priority = 1;
    	$new_wishlist->save();
    	return $new_wishlist;    	
    }

    public function listProducts(Request $request){

        $this->validate($request, [
          'id_customer' => 'bail|required'
        ]);
    	$wishlistProducts = Wishlist::select('id_wishlist')->where('id_customer',$request->id_customer)->get();

        if($wishlistProducts->isEmpty()){
            return response()->json(['error' => 'page_not_found'], 200);
        }

    	foreach ($wishlistProducts as $key => $value) {
    		$value->ProductsName;
            $value->ProductsPrice;
            $value->ProductsImage;
    	} 
        
        $wishlistProducts = json_decode($wishlistProducts,true);

        foreach ($wishlistProducts[0]['products_image'] as $id_image) {
            $idImage = (string) $id_image['id_image'];            //Retriving the cover image id,
            $image = '/img/p/';                                   //Set a path,create a new
            for ($i = 0; $i < strlen($idImage); $i++) {           //variable 'image',insert in 
                $image .= $idImage[$i] . '/';                     //$products array and return
            }
            $image .= $idImage . '.jpg';
            $images[] = array('image'=>$image);
        }
        unset($wishlistProducts[0]['products_image']);
        $a=0;
        foreach ($wishlistProducts[0]['products_price'] as $price) {
            $price1[] = $price;
            $name[] = $wishlistProducts[0]['products_name'][$a];
            $id_product[]= $wishlistProducts[0]['products_name'][$a]['pivot']['id_product'];
            $a++;
        }
        $final = array('name'=>$name,'price'=>$price1,'image'=>$images,'id_product'=>$id_product);
        return $final;
    }

    public function removeProducts(Request $request){
        $this->validate($request, [
          'id_product' => 'bail|required',
          'id_customer' => 'bail|required'
        ]);
        $wishlistProducts = Wishlist::select('id_wishlist')->where('id_customer',34)->get();

        if($wishlistProducts->isEmpty()){
            return response()->json(['error' => 'page_not_found'], 404);
        }
        
        $coisa = WishlistProducts::where('id_wishlist',$wishlistProducts[0]->id_wishlist)->where('id_product',$request->id_product)->delete();

        return response()->json(['succes' => 'ok'], 200);       
    }
}
