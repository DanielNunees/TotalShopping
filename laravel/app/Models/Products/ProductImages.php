<?php

namespace App\Models\Products;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    public $timestamps = false;
    protected $table = 'ps_image';
    protected $primaryKey = 'id_product';
}
