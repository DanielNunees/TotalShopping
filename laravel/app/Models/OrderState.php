<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderState extends Model
{
    //ps_order_state

    public $timestamps = false;
    protected $table = 'ps_order_state';
    protected $primaryKey = 'id_order_state';
}
