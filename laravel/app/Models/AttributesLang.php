<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributesLang extends Model
{
    public $timestamps = false;
    protected $table = 'ps_attribute_lang';
    protected $primaryKey = 'id_attribute';
}
