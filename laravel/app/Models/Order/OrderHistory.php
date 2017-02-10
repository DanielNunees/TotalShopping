<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    //
    public $timestamps = false;
    protected $table = 'ps_order_history';
    protected $primaryKey = 'id_order_history';

    public static function setHistory($history){
    	return OrderHistory::insertGetId($history);
    }
}
