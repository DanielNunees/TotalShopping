<?php

namespace App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products\ProductStock;

class ProductLang extends Model //Nome do produto
{
  	public $timestamps = false;
	protected $table = 'ps_product_lang';
	protected $primaryKey = 'id_product';
    protected $hidden = array('pivot');


    static public function ProductResume($id_product){
        if(is_array($id_product)){
            return ProductLang::whereIn('id_product',$id_product)->where('id_lang',2)->select('name','description','id_product')->get();
        }
        return ProductLang::where('id_product',$id_product)->where('id_lang',2)->select('name','description','id_product')->get();
        
        
    }

  	public function ProductPrice(){ //Select price of all products
    	return $this->hasOne('App\Models\Products\Product','id_product')->select('price');
    } 

    public function ProductStock(){ //Retriving the quantity of each product
    	return $this->hasOne('App\Models\Products\ProductStock','id_product')->where('quantity','>',0)->select('quantity');
    }

    public function ProductImage(){ //Retriving the cover id image from each product
    	return $this->hasOne('App\Models\Products\ProductImages','id_product')->where('cover',1)->select('id_image');
    }

    public function ProductStockComplete(){
        return $this->hasMany('App\Models\Products\ProductStock','id_product')->where('id_product_attribute','!=',0)->where('quantity','>',0)->select('id_product_attribute');
    }

}
