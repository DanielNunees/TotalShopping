<?php

namespace App\Models\Products;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    public $timestamps = false;
    protected $table = 'ps_image';
    protected $primaryKey = 'id_product';

    static public function ProductImages($id_product){
    	if(is_array($id_product)){
    		return ProductImages::whereIn('id_product',$id_product)->select('id_image','id_product')->get();
    	}
    	return ProductImages::where('id_product',$id_product)->select('id_image')->get();
    }
}
