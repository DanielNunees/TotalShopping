<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeGroupLang extends Model
{
    public $timestamps = false;
	protected $table = 'ps_attribute_group_lang';
	protected $primaryKey = 'id_attribute_group';
}
