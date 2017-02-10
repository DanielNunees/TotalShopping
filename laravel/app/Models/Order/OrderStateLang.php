<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderStateLang extends Model
{
    //ps_order_state_lang

    public $timestamps = false;
    protected $table = 'ps_order_state_lang';
    protected $primaryKey = 'id_order_state';


}
