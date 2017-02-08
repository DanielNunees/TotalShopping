<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public $timestamps = false;
    protected $table = 'ps_shop';
    protected $primaryKey = 'id_shop';

    public static function getIdStores(){
    	return Shop::select('id_shop','name')->distinct()->get();
    }


}
