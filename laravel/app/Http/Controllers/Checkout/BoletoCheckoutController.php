<?php

namespace App\Http\Controllers\Checkout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Tools\Tools;
use App\Http\Requests;
use PagSeguro\Library;
use App\Http\Controllers\CartController;
use App\Http\Controllers\userController;
use App\Http\Controllers\myAuthController;
use App\Http\Controllers\OrderController;
use App\Models\State;
use Exception;

class BoletoCheckoutController extends Controller
{
    public static function boletoCheckout(Request $request)
    {   

        if(!is_int(myAuthController::getAuthenticatedUser())){
            return myAuthController::getAuthenticatedUser();
        }
        Library::initialize();
        Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
        Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");
       //Instantiate a new Boleto Object
        $boleto = new \PagSeguro\Domains\Requests\DirectPayment\Boleto();

        /**
         * @todo Change the receiver Email
         */
        $boleto->setReceiverEmail('danielrns15@gmail.com'); 

        // Set the currency
        $boleto->setCurrency("BRL");

        // Add an item for this payment request
        try{
            $cart_products = CartController::loadCart();
        }catch(Exception $e){
            return $e->getMessage(); 
        }
        $price =0;
        $cart_products = $cart_products->toArray();
        foreach ($cart_products as $key => $value) {
            $boleto->addItems()->withParameters(
                $value['id_product'],
                $value['name'].','.$value['attributes'][0]['name'],
                $quantity = $value['quantity'] ,
                number_format($value['product_price']['price'] ,2)
            );
            $price = $price + number_format($quantity * $value['product_price']['price'],2, '.', '');
        }

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $reference = Tools::passwdGen(8,'NO_NUMERIC');
        $boleto->setReference($reference);

        //set extra amount
        //$boleto->setExtraAmount(11.5);

        // Set your customer information.
        // If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
        $userData = userController::loadData();

        $name = $userData['address']['firstname'].' '.$userData['address']['lastname'];
        $cpf = Tools::CPFmask($request->checkoutData['cpf'],'###.###.###-##'); //from request
        $area_code = str_split($userData['address']['phone_mobile'] , 2)[0];
        $phone_mobile = substr($userData['address']['phone_mobile'],2);

        $street = strstr($userData['address']['address1'],',',true);
        $number = substr(strrchr($userData['address']['address1'],','),1);
        $district = $userData['address']['address2'];
        $postal_code = $userData['address']['postcode'];
        $city = $userData['address']['city'];
        $state = State::getIsoCode($userData['address']['id_state']);
        $state =  $state[0]['iso_code'];
        $complement = $userData['address']['other'];
        $birthday = date("d/m/Y",strtotime($userData['user']['birthday']));





        $boleto->setSender()->setName($name);
        $boleto->setSender()->setEmail('v30781034923906770092@sandbox.pagseguro.com.br'); //TODO

        $boleto->setSender()->setPhone()->withParameters(
            $area_code,
            $phone_mobile
        );

        $boleto->setSender()->setDocument()->withParameters(
            'CPF',
            $cpf
        );

        $boleto->setSender()->setHash($request->checkoutData['SenderHash']);

        $boleto->setSender()->setIp('127.0.0.0'); //TODO

        //return $address['address'];
        $boleto->setShipping()->setAddress()->withParameters(
            $street,
            $number,
            $district,
            $postal_code,
            $city,
            $state,
            'BRA',
            $complement
        );


        // Set shipping information for this payment request
        try {
            //Get the crendentials and register the boleto payment

            //$order = OrderController::createOrder();
            
            $result = $boleto->register(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );
            $order = OrderController::createOrder($reference);
            return ($result->getPaymentLink());
            //echo "<pre>";
            //print_r($result);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
            return ($e->getCode());
        }
    }
}
