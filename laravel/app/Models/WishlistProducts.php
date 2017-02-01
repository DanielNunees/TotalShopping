<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistProducts extends Model
{
	public $timestamps = false;
    protected $table = 'ps_wishlist_product';
    protected $primaryKey = 'id_wishlist_product';
    
    public function listProducts(){ 
    	return $this->hasMany('App\Models\WishlistProducts','id_wishlist_product');
    }

    public static function getProducts($id_wishlist){
    	return WishlistProducts::where('id_wishlist',$id_wishlist)->select('id_product')->get();
    }

    public static function addProduct($product){
    	return WishlistProducts::insertGetId($product);
    }

    public static function deleteProduct($id_wishlist,$id_product){
    	return WishlistProducts::where('id_wishlist',$id_wishlist)->where('id_product',$id_product)->delete();
    }
}


