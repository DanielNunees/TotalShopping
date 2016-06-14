<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $table = 'ps_product';
    protected $primaryKey = 'id_product';

    public function ProductCombinationId(){
    	return $this->belongsToMany('App\Models\ProductAttributes','ps_product_attribute_combination','id_product_attribute','id_attribute')->select('id_product_attribute');	
    }
   
}
 