<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attributes;

class ProductAttributes extends Model //Tabela referenete ao(s) atributo(s) de cada produto
{
    public $timestamps = false;
	protected $table = 'ps_product_attribute';
	protected $primaryKey = 'id_product_attribute';

	public function Attributes(){
		return $this->hasMany('App\Models\Attributes','id_product_attribute')->select('id_attribute');
	}

	public function ProductStock(){
		return $this->hasMany('App\Models\ProductStock','id_product_attribute')->select('id_product_attribute')->where('quantity','>',0);
	}



 }
