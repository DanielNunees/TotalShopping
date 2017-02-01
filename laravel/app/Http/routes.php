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
	Route::post('/user/updateOrCreateAddress','userController@updateOrCreateAddress');
	Route::get('/user/loadData','userController@loadData');
	
	/* Wishlist Routes */
	Route::post('/wishlistAddProduct','WishlistController@addProduct');
	Route::get('/wishlist','WishlistController@listProducts');
	Route::post('/removeWishlistProduct','WishlistController@removeProducts');
	
	/* Paymente Routes */
	Route::post('/creditCardCheckout','creditCardCheckoutController@creditCardCheckout');
	Route::get('/getSession','CheckoutController@getSession');
	Route::post('/boletoCheckout','boletoCheckoutController@boletoCheckout');
	Route::post('/order','OrderController@createOrder');
	
	/* Cart Routes */ 
	Route::post('/cartAddProducts','CartController@addProducts');
	Route::post('/cartRemoveProducts','CartController@removeProducts');
	Route::get('/cartLoad','CartController@loadCart');

	/*  Order Historic Routes*/
	Route::get('/historic/getHistoric','HistoricController@getHistoric');
	
	/* Multi Store Routes*/
	Route::get('/multistore/store/{id_store}','MultiStoreController@getProducts')->where('id_store', '[0-9]+');
	
	/* Products Routes */
	Route::get('home', ['as' => 'home', 'uses' => 'homeController@index']);
	Route::get('/product/{id_product}','ProductController@retrivingProduct')->where('id_product', '[0-9]+');
});
