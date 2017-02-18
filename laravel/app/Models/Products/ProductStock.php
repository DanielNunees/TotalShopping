<?php

namespace App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products\AttributesLang;
use App\Models\Products\ProductLang;

class ProductStock extends Model
{
    public $timestamps = false;
    protected $table = 'ps_stock_available';
    protected $primaryKey = 'id_product_attribute';
    protected $hidden = array('pivot');


    static public function RetrivingAttributes($id_product){
        if(is_array($id_product)){
            return ProductStock::whereIn('id_product',$id_product)->where('id_product_attribute','!=',0)->where('quantity','>',0)->select('id_product_attribute','id_product','quantity')->get();
        }
        return ProductStock::where('id_product',$id_product)->where('id_product_attribute','!=',0)->where('quantity','>',0)->select('id_product_attribute','id_product','quantity')->get();
    }


	public function ProductAttributeName(){
    	return $this->belongsToMany('App\Models\Attributes\AttributesLang','ps_product_attribute_combination','id_product_attribute','id_attribute')->select('name')->where('id_lang',2);
    }

    public function ProductName(){
        return $this->hasOne('App\Models\Products\ProductLang','id_product')->select('description');
    }

    static public function ProductQuantity($id_product){
        return ProductStock::where('id_product',$id_product)->where('id_product_attribute','>',0)->select('quantity','id_product_attribute')->get();
    }

    static public function updateQuantity($id_product,$id_product_attribute,$quantity){
        ProductStock::where('id_product',$id_product)->where('id_product_attribute',$id_product_attribute)->decrement('quantity',$quantity);
        return ProductStock::where('id_product',$id_product)->where('id_product_attribute',0)->decrement('quantity',$quantity);
    }

	
}
