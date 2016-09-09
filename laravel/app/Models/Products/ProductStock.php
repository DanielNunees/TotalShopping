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

	public function ProductAttributeName(){
    	return $this->belongsToMany('App\Models\Attributes\AttributesLang','ps_product_attribute_combination','id_product_attribute','id_attribute')->select('name')->where('id_lang',2);
    }

    public function ProductName(){
        return $this->hasOne('App\Models\Products\ProductLang','id_product')->select('description');
    }



	
}
