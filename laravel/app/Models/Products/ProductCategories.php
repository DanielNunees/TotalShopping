<?php

namespace App\Models\Products;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    public $timestamps = false;
    protected $table = 'ps_category_product';
    protected $primaryKey = 'id_category';

   public static function getProducts($id_category){
   		return ProductCategories::where('id_category',$id_category)->select('id_product')->get();
   }
}
 