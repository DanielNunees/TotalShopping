<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    //ps_guest

    public $timestamps = false;
    protected $table = 'ps_guest';
    protected $primaryKey = 'id_guest';
}
