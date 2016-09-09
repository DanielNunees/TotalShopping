<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //ps_orders
    public $timestamps = false;
	protected $table = 'ps_orders';
	protected $primaryKey = 'id_order';
}
