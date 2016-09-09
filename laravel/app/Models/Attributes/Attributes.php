<?php

namespace App\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    public $timestamps = false;
	protected $table = 'ps_attribute';
	protected $primaryKey = 'id_attribute';

	public function AttributesGroupLang(){
		return $this->belongsTo('\App\Models\Attributes\AttributeGroupLang','id_attribute_group')->select('name')->where('id_lang',2);
	}
	

}
