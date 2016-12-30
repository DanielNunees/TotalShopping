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
	
	/* User Routes */
	Route::post('/user/register','userController@register');
	Route::post('/user/auth','myAuthController@login');
	Route::post('/user/createAddress','userController@createAddress');
	Route::post('/user/updateAddress','userController@updateAddress');
	Route::post('/user/loadData','userController@loadData');
	
	/* Wishlist Routes */
	Route::post('/createWishlist','WishlistController@addProduct');
	Route::post('/wishlist','WishlistController@listProducts');
	Route::post('/removeWishlistProduct','WishlistController@removeProducts');
	
	/* Paymente Routes */
	Route::post('/creditCardCheckout','creditCardCheckoutController@creditCardCheckout');
	Route::post('/getSession','CheckoutController@getSession');
	Route::post('/boletoCheckout','boletoCheckoutController@boletoCheckout');
	Route::post('/order','OrderController@createOrder');
	
	/* Cart Routes */ 
	Route::post('/cartAddProducts','cartController@addProducts');
	Route::post('/cartRemoveProducts','cartController@removeProducts');
	Route::post('/cartLoad','cartController@loadCart');
	

	
	/* Products Routes */
	Route::get('home', ['as' => 'home', 'uses' => 'ProductHomeController@index']);
	Route::get('/product/{id_product}','ProductController@retrivingProduct')->where('id_product', '[0-9]+');
});
