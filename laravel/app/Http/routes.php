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
	Route::post('/creditCardCheckout','Checkout\CreditCardCheckoutController@creditCardCheckout');
	Route::get('/getSession','Checkout\CheckoutController@getSession');
	Route::post('/boletoCheckout','Checkout\BoletoCheckoutController@boletoCheckout');
	Route::post('/order','OrderController@createOrder');
	
	/* Cart Routes */ 
	Route::post('/cartAddProducts','CartController@addProducts');
	Route::post('/cartRemoveProducts','CartController@removeProducts');
	Route::get('/cartLoad','CartController@loadCart');

	/*  Order Historic Routes*/
	Route::get('/historic/getHistoric','HistoricController@getHistoric');
	
	/* Multi Store Routes*/
	Route::get('/multistore/getStores','MultiStoreController@getStores');
	Route::get('/multistore/store/{id_store}/{page}','MultiStoreController@getProducts')->where('id_store', '[0-9]+')->where('page', '[0-9]+');
	
	/* Products Routes */
	Route::get('home/{page}', ['as' => 'home', 'uses' => 'homeController@index'])->where('id_product', '[0-9]+');;
	Route::get('/product/{id_product}','ProductController@retrivingProduct')->where('id_product', '[0-9]+');

	/* TRANSACTION ROUTES*/
	Route::get('searchTransacion/byCode','Transactions\SearchTransactionByCode@getTransaction');
	Route::get('searchTransacion/byDate','Transactions\SearchTransactionByDate@getTransaction');
	Route::get('searchTransacion/byReference','Transactions\SearchTransactionByReference@getTransaction');
	Route::get('searchTransacion/Abandoned','Transactions\SearchTransactionAbandoned@getTransaction');

	/* INTALLMENTS ROUTE*/
	Route::get('installment/getInstallments/{amount}','InstallmentsController@getInstallments')->where('amount','[0-9]+');

	/* TEST ROUTES*/

	Route::get('/teste','boletoTest@checkoutBoleto');
});
