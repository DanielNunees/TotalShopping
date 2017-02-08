<?php

namespace App\Models\Products;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $table = 'ps_product';
    protected $primaryKey = 'id_product';

    public static function getShopId($id_product){
    	return Product::where('id_product',$id_product)->select('id_shop_default')->get();
    }
    public static function getAllIds(){
    	return Product::select('id_product')->get();
    }

    public static function getProductsFromStore($id_store){
    	return Product::where('id_shop_default',$id_store)->select('id_product')->get();
    }

    public static function getCountProductsFromStore($id_store){
        return Product::where('id_shop_default',$id_store)->count();
    }
   
}
 