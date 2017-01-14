<?php

namespace App\Models\Products;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $table = 'ps_product';
    protected $primaryKey = 'id_product';

    public function ProductCombinationId(){
    	return $this->belongsToMany('App\Models\Products\ProductAttributes','ps_product_attribute_combination','id_product_attribute','id_attribute')->select('id_product_attribute');	
    }

    public static function getShopId($id_product){
    	return Product::where('id_product',$id_product)->select('id_shop_default')->get();
    }
   
}
 