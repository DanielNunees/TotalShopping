<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    //ps_cart_product
    public $timestamps = false;
    protected $table = 'ps_cart_product';
    protected $primaryKey = 'id_cart';

}
