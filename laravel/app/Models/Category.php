<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $table = 'ps_category';
    protected $primaryKey = 'id_category';
   
}
 