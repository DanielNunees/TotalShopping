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

    	$wishlist = WishlistController::listProducts();
        //return $wishlist;
        if(isset($wishlist[0]['product']))
        foreach ($wishlist as $key => $value) {
            if($request->id_product == $value->id_product)
                return response()->json(['alert' => 'Product Already In Wishlist'], 200);
        }
        //return $wishlist[0]['id_wishlist'];
    	$product = [
    	'id_wishlist' => $wishlist[0]['id_wishlist'],
    	'id_product' => $request->id_product,
    	'id_product_attribute' => $request->id_product_attribute,
    	'quantity' => 1,
    	'priority' => 1
    	];
    	return WishlistProducts::addProduct($product);   	
    }

    public function listProducts(){

        $id_customer = myAuthController::getAuthenticatedUser();
        $wishlist_id =  Wishlist::getIdWishlist($id_customer);
        $products_id = WishlistProducts::getProducts($wishlist_id[0]['id_wishlist']);
        
        if($products_id->isEmpty())
            return $wishlist_id; 

        foreach ($products_id as $key => $value) {
            $product2 = ProductController::retrivingProduct($value->id_product);

            sort($product2['attributes']);
           
            $attribute = array_search($value->id_product_attribute, array_column($product2['attributes'],'id_product_attribute'));
            $product2['attributes'] = array_slice($product2['attributes'], -(count($product2['attributes'])-$attribute),1);

            $products_id[$key]->product = $product2;
            $products_id[$key]->id_wishlist = $wishlist_id[0]['id_wishlist'];
        }
        return $products_id;
    }

    public function removeProducts(Request $request){
         $this->validate($request, [
          'id_product' => 'bail|required',
        ]);

        $wishlist = WishlistController::listProducts();
        if(isset($wishlist[0]['product']))
        foreach ($wishlist as $key => $value) {
            if($request->id_product == $value->id_product){
               WishlistProducts::deleteProduct($wishlist[0]['id_wishlist'],$value->id_product);
               return response()->json(['Success' => 'Product removed'], 200); 
            }
        }
        
       return response()->json(['Error' => 'Product not removed'], 200);

               
    }
}
