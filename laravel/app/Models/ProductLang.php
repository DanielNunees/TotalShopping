<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductStock;

class ProductLang extends Model //Nome do produto
{
  	public $timestamps = false;
	protected $table = 'ps_product_lang';
	protected $primaryKey = 'id_product';

  	public function ProductPrice(){ //Select price of all products
    	return $this->hasOne('App\Models\Product','id_product')->select('price');
    } 

    public function ProductStock(){ //Retriving the quantity of each product
    	return $this->hasOne('App\Models\ProductStock','id_product')->where('quantity','>',0)->select('quantity');
    }

    public function ProductImage(){ //Retriving the cover id image from each product
    	return $this->hasOne('App\Models\ProductImages','id_product')->where('cover',1)->select('id_image');
    }

    public function ProductImages(){ //Retriving the cover id image from each product
        return $this->hasMany('App\Models\ProductImages','id_product')->select('id_image');
    }

    public function ProductStockComplete(){
        return $this->hasMany('App\Models\ProductStock','id_product')->where('id_product_attribute','!=',0)->where('quantity','>',0)->select('id_product_attribute');
    }
    public function ProductAttributeName(){
        return $this->belongsToMany('App\Models\AttributesLang','ps_product_attribute_combination','id_product_attribute','id_attribute')->select('name')->where('id_lang',2);
    }
}
