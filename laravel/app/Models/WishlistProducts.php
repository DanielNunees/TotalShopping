<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistProducts extends Model
{
	public $timestamps = false;
    protected $table = 'ps_wishlist_product';
    protected $primaryKey = 'id_wishlist_product';
    
    public function listProducts(){ 
    	return $this->hasMany('App\Models\WishlistProducts','id_wishlist_product');
    }
}
