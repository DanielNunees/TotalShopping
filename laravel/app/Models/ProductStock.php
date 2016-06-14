<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttributesLang;
use App\Models\ProductLang;

class ProductStock extends Model
{
    public $timestamps = false;
    protected $table = 'ps_stock_available';
    protected $primaryKey = 'id_product_attribute';

	public function ProductAttributeName(){
    	return $this->belongsToMany('App\Models\AttributesLang','ps_product_attribute_combination','id_product_attribute','id_attribute')->select('name')->where('id_lang',2);
    }

    public function ProductName(){
        return $this->hasOne('App\Models\ProductLang','id_product')->select('description');
    }



	
}
