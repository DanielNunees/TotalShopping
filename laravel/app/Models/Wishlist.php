<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\WishlistProducts;

class Wishlist extends Model
{
    public $timestamps = false;
    protected $table = 'ps_wishlist';
    protected $primaryKey = 'id_wishlist';

    public static function createWishlist($Wishlist){
        return Wishlist::insertGetId($Wishlist);
    }
    public function listProducts(){ 
    	return $this->hasMany('App\Models\WishlistProducts','id_wishlist');
    }

    public static function getIdWishlist($id_customer){
        return Wishlist::where('id_customer',$id_customer)->select('id_wishlist')->get();
    }

    public function ProductCombinationId(){
        return $this->belongsToMany('App\Models\WishlistProducts','ps_product_attribute_combination','id_product_attribute','id_attribute')->select('id_product_attribute');  
    }


}

