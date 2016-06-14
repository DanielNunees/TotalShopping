<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRegister extends Model
{
    public $timestamps = false;
    protected $table = 'ps_customer';
    protected $primaryKey = 'id_customer';

   
}
