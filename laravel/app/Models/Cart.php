<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //ps_cart
    public $timestamps = false;
    protected $table = 'ps_cart';
    protected $primaryKey = 'id_cart';

    static public function RetrivingCartId($id_customer){
         return Cart::select('id_cart')->where('id_customer',$id_customer)->orderBy('date_add','dsc')->first();
    }
}
