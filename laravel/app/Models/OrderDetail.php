<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //ps_order_detail
    public $timestamps = false;
    protected $table = 'ps_order_detail';
    protected $primaryKey = 'id_order_detail';
}
