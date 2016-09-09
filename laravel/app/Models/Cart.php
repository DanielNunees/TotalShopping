<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //ps_cart
    public $timestamps = false;
    protected $table = 'ps_cart';
    protected $primaryKey = 'id_cart';
}
