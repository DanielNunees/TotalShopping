<?php

namespace App\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

class AttributesLang extends Model
{
    public $timestamps = false;
    protected $table = 'ps_attribute_lang';
    protected $primaryKey = 'id_attribute';

    public static function getAttributesNames($id_attribute){
    	if(is_array($id_attribute))
    		return AttributesLang::whereIn('id_attribute',$id_attribute)->select('name','id_attribute')->where('id_lang',2)->get();
    	return AttributesLang::where('id_attribute',$id_attribute)->select('name','id_attribute')->where('id_land',2)->get();
    }
}
