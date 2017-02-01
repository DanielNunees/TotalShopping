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
            return ProductLang::whereIn('id_product',$id_product)->where('id_lang',2)->select('name','description','id_product')->distinct()->get();
        }
        return ProductLang::where('id_product',$id_product)->where('id_lang',2)->select('name','description','id_product')->distinct()->get();
        
        
    }

  	public function ProductPrice(){ //Select price of all products
    	return $this->hasOne('App\Models\Products\Product','id_product')->select('price');
    } 

}
