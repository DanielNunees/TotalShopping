<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'ps_customer';
    protected $primaryKey = 'id_customer';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthPassword () {

        return $this->passwd;

    }

    public static function getSecureKey($id_customer){
        return  User::select("secure_key")->where('id_customer',$id_customer)->get();
    }

    public static function getCustomerWithEmail($email){
        return User::where('email',$email)->get();
    }

    public static function getCustomerWithId($id_customer){
        return User::where('id_customer',$id_customer)->where('active',1)->select('lastname','firstname','email','birthday')->get();
    }

    public static function registerUser($user){
        return User::insertGetId($user);
    }

}
