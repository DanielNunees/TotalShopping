<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Models\ProductLang;
use App\Models\ProductPrice;
Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('welcome');
    });
});

Route::group(['middleware' => 'cors'], function () {
	
	Route::post('/user/register','userController@register');
	Route::post('/user/auth','myAuthController@auth');
	Route::post('/user/createAddress','userController@createAddress');
	Route::post('/user/updateAddress','userController@updateAddress');
	Route::post('/user/loadData','userController@loadData');
	Route::post('/createWishlist','WishlistController@addProduct');
	Route::post('/wishlist','WishlistController@listProducts');
	Route::post('/removeWishlistProduct','WishlistController@removeProducts');
	Route::post('/creditCardCheckout','creditCardCheckoutController@creditCardCheckout');
	Route::post('/getSession','CheckoutController@getSession');
	Route::post('/boletoCheckout','boletoCheckoutController@boletoCheckout');
	Route::post('/cartAddProducts','cartController@addProducts');
	Route::post('/cartRemoveProducts','cartController@removeProducts');
	Route::post('/newGuest','GuestController@newGuest');

	

	Route::get('home', ['as' => 'home', 'uses' => 'ProductHomeController@index']);
	Route::get('/product/{id_product}','ProductController@index')->where('id_product', '[0-9]+');
});
