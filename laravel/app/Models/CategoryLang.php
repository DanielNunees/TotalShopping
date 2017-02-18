<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CategoryLang extends Model
{
    public $timestamps = false;
    protected $table = 'ps_category_lang';
    protected $primaryKey = 'id_category';

    public static function getCategories(){
    	return CategoryLang::select('id_category','name')->where('id_lang',2)->distinct()->get();
    }
   
}
 