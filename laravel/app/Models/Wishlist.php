<?php

namespace App\Models;
use App\Models\Products\Product;
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

    public function ProductsPrice(){
    	return $this->belongsToMany('App\Models\Products\Product','ps_wishlist_product','id_wishlist','id_product')->select('price');
    }

    public function ProductsName(){
    	return $this->belongsToMany('App\Models\Products\ProductLang','ps_wishlist_product','id_wishlist','id_product')->select('name')->where('id_lang',2);
    }

    public function ProductsImage(){
    	return $this->belongsToMany('App\Models\Products\ProductImages','ps_wishlist_product','id_wishlist','id_product')->select('id_image')->where('cover',1);
    }

    public function ProductsId(){
        return $this->hasMany('App\Models\WishlistProducts','id_wishlist')->select('id_product');
    }


}

