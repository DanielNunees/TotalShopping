<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Log;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Hash;


class myAuthController extends Controller
{

    public function __construct()
   {
       // Apply the jwt.auth middleware to all methods in this controller
       // except for the authenticate method. We don't want to prevent
       // the user from retrieving their token if they don't already have it
       $this->middleware('jwt.auth',['except' => ['login']]);
   }
    public function login(Request $request)
    {
        $this->validate($request, [
          'email' => 'bail|required|max:128',
          'password' => 'bail|required|max:32'
        ]);
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt((array('email' => $request->email, 'password' => $request->password)))) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        $user = Auth::User();
        $id_customer = $user->id_customer;
        return response()->json(compact('token'));
    }

    public static function getAuthenticatedUser()
{
    try {

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }

    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

        return response()->json(['token_expired'], $e->getStatusCode());

    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

        return response()->json(['token_invalid'], $e->getStatusCode());

    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

        return response()->json(['token_absent'], $e->getStatusCode());

    }

    // the token is valid and we have found the user via the sub claim
    $id_customer = $user->id_customer;
    return $id_customer;
}
}
