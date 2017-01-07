<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Http\Requests;
use App\Models\Products\Product;
use App\Models\WishlistProducts;
use App\Http\Controllers\myAuthController;

class WishlistController extends Controller
{
    
    public function addProduct(Request $request){
        $id_customer = myAuthController::getAuthenticatedUser();
    	
        $this->validate($request, [
          'id_product' => 'bail|required',
          'id_product_attribute' => 'bail|required',
      	]);

    	$wishlist = Wishlist::select('id_wishlist')->where('id_customer',$id_customer)->get();
        if($wishlist->isEmpty()){
            return response()->json(['error' => 'Wishlist not created'],400);
        }

        foreach ($wishlist as $value) {
           $value->ProductsId;
        }
        $wishlist = json_decode($wishlist,true);
        //return $wishlist[0]['products_id'];

        foreach($wishlist[0]['products_id'] as $id_products){
            foreach ($id_products as $id) {
               if($id == $request->id_product){
                    return response()->json(['error' => 'Produto já esta na wishlist'],409);
                }
            }
        }

    	$new_wishlist = new WishlistProducts;
    	$new_wishlist->id_wishlist = $wishlist[0]['id_wishlist'];
    	$new_wishlist->id_product = $request->id_product;
    	$new_wishlist->id_product_attribute = $request->id_product_attribute;
    	$new_wishlist->quantity = 1;
    	$new_wishlist->priority = 1;
    	$new_wishlist->save();
    	return $new_wishlist;    	
    }

    public function listProducts(){

        $id_customer = myAuthController::getAuthenticatedUser();
    	$wishlistProducts = Wishlist::where('id_customer',$id_customer)->select('id_wishlist')->get();
    
        if($wishlistProducts->isEmpty()){
            return response()->json(['error' => 'Wishlist not created'],400);
        }

    	foreach ($wishlistProducts as $key => $value) {
            
            if($value->ProductsName->isEmpty()||$value->ProductsName->isEmpty()||$value->ProductsName->isEmpty()){
                return response()->json(['error' => 'Wishlist is empty'],404);
            }
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
            $id_product[]= $wishlistProducts[0]['products_price'][$a]['pivot']['id_product'];
            $a++;
        }
        $final = array('name'=>$name,'price'=>$price1,'image'=>$images,'id_product'=>$id_product);

        return $final;
    }

    public function removeProducts(Request $request){
        $id_customer = myAuthController::getAuthenticatedUser();
        $this->validate($request, [
          'id_product' => 'bail|required'
        ]);
        $wishlistProducts = Wishlist::select('id_wishlist')->where('id_customer',$id_customer)->get();

        if($wishlistProducts->isEmpty()){
            return response()->json(['error' => 'page_not_found'], 404);
        }
        
        $coisa = WishlistProducts::where('id_wishlist',$wishlistProducts[0]->id_wishlist)->where('id_product',$request->id_product)->delete();

        return response()->json(['succes' => 'ok'], 200);       
    }
}
