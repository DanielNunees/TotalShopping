<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeCombination extends Model //Tabela de todos os atributos

{
    public $timestamps = false;
    protected $table = 'ps_product_attribute_combination';
    protected $primaryKey = 'id_product_attribute';
}
