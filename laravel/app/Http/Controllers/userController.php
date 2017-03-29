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
use App\Http\Controllers\myAuthController;
use App\Http\Controllers\WishlistController;
//use App\services\PSWebServiceLibrary;
class userController extends Controller
{
	public function register(Request $request)
  {
    $this->validate($request, [
      'email' => 'bail|required|max:128',
      'password' => 'bail|required|max:32|min:6',
      'name' => 'required|max:32',
      'birth' => 'required|date',
      'lastName' => 'required|max:32',
      'gender' => 'required|numeric',
      'newsletter' => 'boolean'
      ]);

    // /return $request->input('email');
    $user = User::getCustomerWithEmail($request->input('email'));
    if(!$user->isEmpty()){
      return response()->json(['error' => 'user_registered'], 400);
    }
      $today = date("Y-m-d H:i:s");
      $user = [
      'firstname' => $request->input('name'),
      'email' => $request->input('email'),
      'lastName' => $request->input('lastName'),
	     //$user->passwd = md5('DrjFxIWD1Ea1un95kvDBEfwNNE1SzPdZg4jqzkgKW7UWbRUXD50HULgm'.$request->password);
      'passwd' => bcrypt($request->password),
      'secure_key' =>  md5(uniqid(rand(), true)),
      'active' =>  1,
      'optin' =>  1,
      'id_lang' => 2,
      'id_gender' =>  $request->input('gender'),
      'newsletter' =>  ($request->input('newsletter')==1)? 1 : 0,
      'birthday' =>  $request->input('birth'),
      
      'date_add' =>  $today,
      'date_upd' =>  $today    	
      ];
      $id_customer = User::registerUser($user);
      WishlistController::createWishlist($id_customer);

      return response()->json(['ok' => 'registered'], 200);

  }

  public static function loadData(){
    $id_customer = myAuthController::getAuthenticatedUser();
    if(!is_numeric($id_customer)){
        return $id_customer;
    }

    $address = Address::getAddress($id_customer);

    $user = User::getCustomerWithId($id_customer);

    if($address->isEmpty()){
      return array('user'=>$user[0], 'states'=>UserController::getStates());
    }
    $address[0]->state = userController::getStates($address[0]->id_state);
    $array = array('address' => $address[0],'user'=>$user[0],'states'=>UserController::getStates());

    return $array;
  }

  public function updateOrCreateAddress(Request $request){
    $this->validate($request, [
      'postcode' => 'bail|required',
      'address1' => 'bail|required',
      'address2' => 'bail|required',
      'city' => 'bail|required',
      'phoneMobile' =>'bail|required',
      'id_state' => 'bail|required|numeric'
    ]);
    $id_customer = myAuthController::getAuthenticatedUser();
    if(!is_numeric($id_customer)){
        return $id_customer;
    }
    $user = User::getCustomerWithId($id_customer);

    $today = date("Y-m-d H:i:s");
    $request->phoneMobile = str_replace(array( '(', ')','-' ), '', $request->phoneMobile);
    $address =array(
      'address1' => $request->address1,
      'address2' => $request->address2,
      'postcode' => $request->postcode,
      'city' => $request->city,
      'phone' => $request->phone,
      'phone_mobile' => $request->phoneMobile,
      'firstname' => $user[0]->firstname,
      'lastname' => $user[0]->lastname,
      'id_state' => $request->id_state,
      'id_customer' => $id_customer,
      'id_country' => 58,
      'date_add' => $today,
      'date_upd' => $today
    );
    //return $id_customer;

    $address = Address::updateOrCreateAddress($id_customer,$address);
    return userController::loadData();
  }

  public static function getAddress($id_customer){
    $address = Address::getIdAdressFromCustomer($id_customer);
    if($address->isEmpty()) return false;
    return $address = $address[0]['id_address'];
  }

  public static function getStates($state_id=0){
    $states = array('Acre'=>313,'Alagoas'=>314,'Amapa'=>315,'Amazonas'=>316,'Bahia'=>317,'Ceara'=>318,'Distrito Federal'=>319,'Espirito Santo'=>320,'Goias'=>321,'Maranhao'=>322,'Mato Grosso'=>323,'Mato Grosso do Sul'=>324,'Minas Gerais'=>325,'Para'=>326,'Paraiba'=>327,'Parana'=>328,'Pernanbuco'=>329,'Piaui'=>330,'Rio de Janeiro'=>331,'Rio Grande do Norte'=>332,'Rio Grande do Sul'=>333,'Rondonia'=>334,'Roraima'=>335,'Santa Catarina'=>336,'Sao Paulo'=>337,'Sergipe'=>338,'Tocantins'=>339);
      
      foreach ($states as $key => $value) {
        if($state_id==$value){
          return $key;
          break;
        }
        
      }
    return $states;
  }

}
