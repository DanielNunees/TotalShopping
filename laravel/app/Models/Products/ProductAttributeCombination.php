<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeCombination extends Model //Tabela de todos os atributos

{
    public $timestamps = false;
    protected $table = 'ps_product_attribute_combination';
    protected $primaryKey = 'id_product_attribute';

    public static function getAttributeId($id_product_attribute){
    	if(is_array($id_product_attribute))
    		return ProductAttributeCombination::whereIn('id_product_attribute',$id_product_attribute)->get();
    	return ProductAttributeCombination::where('id_product_attribute',$id_product_attribute)->get();
    } 
}
