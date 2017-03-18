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
    public static function createWishlist($id_customer){
        $token = random_bytes(8);
        $token = bin2hex($token);
        $today = date("Y-m-d H:i:s");
      $wishlist =[
          'id_customer' => $id_customer,
          'token' => strtoupper($token),
          'name' => 'Minha lista de desejos',
          'id_shop' => 1, //TODO
          'id_shop_group' => 1,
          'date_add' => $today,
          'date_upd' => $today,
          'default' => 1
          ];

      return Wishlist::createWishlist($wishlist);
    }

    public function addProduct(Request $request){

        $this->validate($request, [
          'id_product' => 'bail|required',
          'id_product_attribute' => 'bail|required',
      	]);

        $id_customer = myAuthController::getAuthenticatedUser();

        if(!is_numeric($id_customer)){
          return $id_customer; 
        }

    	  $wishlist = WishlistController::listProducts();
        $wishlist_id =  Wishlist::getIdWishlist($id_customer);
        if(isset($wishlist[0]['product']))
        foreach ($wishlist as $key => $value) {
            if($request->id_product == $value->id_product)
                return response()->json(['alert' => 'Product Already In Wishlist'], 200);
        }
        //return $wishlist[0]['id_wishlist'];
    	$product = [
    	'id_wishlist' => $wishlist_id[0]['id_wishlist'],
    	'id_product' => $request->id_product,
    	'id_product_attribute' => $request->id_product_attribute,
    	'quantity' => 1,
    	'priority' => 1
    	];
    	return WishlistProducts::addProduct($product);   	
    }

    public function listProducts(){

        $id_customer = myAuthController::getAuthenticatedUser();

        if(!is_numeric($id_customer)){
          return $id_customer; 
        }

        $wishlist_id =  Wishlist::getIdWishlist($id_customer);

        if($wishlist_id->isEmpty()) return response()->json(['Error' => 'Wishlist Not Created'], 500);  
        $products_id = WishlistProducts::getProducts($wishlist_id[0]['id_wishlist']);
        if($products_id->isEmpty())
            return $wishlist_id;
        $products_id = $products_id->pluck('id_product')->toArray();
        return $product2 = ProductController::retrivingProduct($products_id);
    }

    public function removeProducts(Request $request){
         $this->validate($request, [
          'id_product' => 'bail|required',
        ]);

        $id_customer = myAuthController::getAuthenticatedUser();

        if(!is_numeric($id_customer)){
          return $id_customer; 
        }

        $wishlist = WishlistController::listProducts();
        $wishlist_id =  Wishlist::getIdWishlist($id_customer);
        if(isset($wishlist[0]['id_product']))
        foreach ($wishlist as $key => $value) {
            if($request->id_product == $value->id_product){
               WishlistProducts::deleteProduct($wishlist_id[0]['id_wishlist'],$value->id_product);
               return response()->json(['Success' => 'Product removed'], 200); 
            }
        }
        
       return response()->json(['Error' => 'Product not removed'], 200);

               
    }
}
