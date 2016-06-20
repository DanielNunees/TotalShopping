<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use App\Http\Requests;
use App\Models\Wishlist;
use App\Models\UserRegister;
use App\Models\Address;
use App\User;
use App\Models\Country;
//use App\services\PSWebServiceLibrary; 

class userController extends Controller
{
	public function register(Request $request)
  {

    $this->validate($request, [
      'email' => 'bail|required|max:128',
      'password' => 'bail|required|max:32',
      'name' => 'required|max:32',
      'birth' => 'required|date',
      'lastName' => 'required|max:32',
      'gender' => 'required|boolean',
      'newsletter' => 'boolean'
      ]);

    $user = UserRegister::where('email',$request->input('email'))->get();

    if(sizeof($user)==1){
      return response()->json(['error' => 'user_registered'], 400);
    }else{
      $user = new UserRegister;
      $user->firstname = $request->input('name');
      $user->email = $request->input('email');
      $user->lastName = $request->input('lastName');
	    	//$user->passwd = md5('DrjFxIWD1Ea1un95kvDBEfwNNE1SzPdZg4jqzkgKW7UWbRUXD50HULgm'.$request->password);
      $user->passwd = bcrypt($request->password);
      $user->secure_key = md5(uniqid(rand(), true));
      $user->active = 1;
      $user->optin = 1;
      $user->id_lang =2;
      $user->id_gender = $request->input('gender');
      $user->newsletter = ($request->input('newsletter')==1)? 1 : 0;
      $user->birthday = $request->input('birth');
      $today = date("Y-m-d H:i:s");
      $user->date_add = $today;
      $user->date_upd = $today;	    	

      $user->save();

      $user1 = UserRegister::where('email',$request->input('email'))->get();

      $wishlist = new Wishlist;

      $wishlist->id_customer = $user1[0]->id_customer;
      $token = openssl_random_pseudo_bytes(8);
      $token = bin2hex($token);
      $wishlist->token = strtoupper($token);
      $wishlist->name = 'Minha lista de desejos';
      $wishlist->id_shop = 1;
      $wishlist->id_shop_group = 1;
      $today = date("Y-m-d H:i:s");
      $wishlist->date_add = $today;
      $wishlist->date_upd = $today;
      $wishlist->default = 1;

      $wishlist->save();


      return response()->json(['sucess' => 'everything_is_fine'], 200);

    }
  }

  public function loadData(Request $request){

    $this->validate($request, [
      'id_customer' => 'bail|required',
    ]);

    $address = Address::where('id_customer',$request->id_customer)->where('active','1')->select('id_state','lastname','firstname','address1','address2','postcode','city','phone','phone_mobile')->get();

    if($address->isEmpty()){
      return response()->json(['error' => 'is_empty'], 400);
    }


    return $address;
  }

  public function createAddress(Request $request){

    $this->validate($request, [
      'postcode' => 'numeric|bail|required',
      'id_customer' => 'bail|numeric|required',
      'address1' => 'bail|required',
      'address2' => 'bail|required',
      'city' => 'bail|required',
      'phone_mobile' =>'bail|required|numeric',
      'id_state' => 'bail|required|numeric'
    ]);

    $user = User::where('id_customer',$request->id_customer)->select('lastname','firstname')->get();

    $address = new Address;
    //eturn $user[0]->lastname;

    $address->address1 = $request->address1;
    $address->address2 = $request->address2;
    $address->postcode = $request->postcode;
    $address->city = $request->city;
    $address->phone = $request->phone;
    $address->phone_mobile = $request->phone_mobile;
    $address->firstname = $user[0]->firstname;
    $address->lastname = $user[0]->lastname;
    $address->id_state = $request->id_state;
    $address->id_customer = $request->id_customer;
    $address->id_country = 58;
    $today = date("Y-m-d H:i:s");
    $address->date_add = $today;
    $address->date_upd = $today;

    $address->save();

    return $address;
  }

  public function updateAddress(Request $request){

    $this->validate($request, [
      'postcode' => 'numeric|bail|required',
      'id_customer' => 'bail|numeric|required',
      'address1' => 'bail|required',
      'address2' => 'bail|required',
      'city' => 'bail|required',
      'phone_mobile' =>'bail|required|numeric',
      'id_state' => 'bail|required|numeric'
    ]);

    $today = date("Y-m-d H:i:s");
    $user = User::where('id_customer',$request->id_customer)->select('lastname','firstname')->get();

    $address = Address::where('id_customer',$request->id_customer)->update(['address1' => $request->address1,'address2'=>$request->address2,
                                                           'postcode'=>$request->postcode,'city'=>$request->city,'phone'=>$request->phone,'phone_mobile'=>$request->phone_mobile,'id_customer'=>$request->id_customer,'date_add'=>$today,'date_upd'=>$today,'id_country'=>'58','id_state'=>$request->id_state,'firstname'=>$user[0]->firstname,'lastname'=>$user[0]->lastname]);


    //$address = new Address;
    //eturn $user[0]->lastname;

    //$address->address1 = 'fudeu95';

    /*$address->address2 = $request->address2;
    $address->postcode = $request->postcode;
    $address->city = $request->city;
    $address->phone = $request->phone;
    $address->phone_mobile = $request->phone_mobile;
    $address->firstname = $user[0]->firstname;
    $address->lastname = $user[0]->lastname;
    $address->id_state = $request->id_state;
    $address->id_customer = $request->id_customer;
    $address->id_country = 58;
    
    $address->date_add = $today;
    $address->date_upd = $today;*/

    //$address->save();

    return $address;
  }

}
