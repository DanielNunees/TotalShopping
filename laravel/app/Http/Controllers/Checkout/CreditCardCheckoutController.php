<?php

namespace App\Http\Controllers\Checkout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Tools\Tools;
use App\Http\Requests;
use App\Http\Controllers\myAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\userController;
use App\Models\State;
use Exception;

class CreditCardCheckoutController extends Controller
{
    public static function creditCardCheckout(Request $request)
    {   

        if(!is_int(myAuthController::getAuthenticatedUser())){
            return myAuthController::getAuthenticatedUser();
        }
        
        \PagSeguro\Library::initialize();
        \PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
        \PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

        //Instantiate a new direct payment request, using Credit Card
        $creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();

        /**
         * @todo Change the receiver Email
         */ 
        $creditCard->setReceiverEmail('danielrns15@gmail.com');

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $reference = Tools::passwdGen(8,'NO_NUMERIC');
        $creditCard->setReference($reference);

        // Set the currency
        $creditCard->setCurrency("BRL");

        // Add an item for this payment request
        try{
            $cart_products = CartController::loadCart();
        }catch(Exception $e){
            return $e->getMessage(); 
        }
        $price =0;
        $cart_products = $cart_products->toArray();
        foreach ($cart_products as $key => $value) {
            $creditCard->addItems()->withParameters(
                $value['id_product'],
                $value['name'].','.$value['attributes'][0]['name'],
                $quantity = $value['quantity'] ,
                number_format($value['product_price']['price'] ,2)
            );
            $price = $price + number_format($quantity * $value['product_price']['price'],2, '.', '');
        }

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
        
        $creditCard->setSender()->setName($name);
        $creditCard->setSender()->setEmail('v30781034923906770092@sandbox.pagseguro.com.br'); //TODO

        //sreturn $area_code;

        $creditCard->setSender()->setPhone()->withParameters(
            $area_code,
            $phone_mobile
        );

        $creditCard->setSender()->setDocument()->withParameters(
            'CPF',
            $cpf
        );

        $creditCard->setSender()->setHash($request->checkoutData['SenderHash']);

        $creditCard->setSender()->setIp('127.0.0.0'); //TODO

        // Set shipping information for this payment request
        $creditCard->setShipping()->setAddress()->withParameters(
            $street,
            $number,
            $district,
            $postal_code,
            $city,
            $state,
            'BRA',
            $complement
        );

        //Set billing information for credit card
        $creditCard->setBilling()->setAddress()->withParameters(
            $street,
            $number,
            $district,
            $postal_code,
            $city,
            $state,
            'BRA',
            $complement
        );

        // Set credit card token
        $creditCard->setToken($request->checkoutData['creditCardToken']);

        // Set the installment quantity and value (could be obtained using the Installments
        // service, that have an example here in \public\getInstallments.php)
        $creditCard->setInstallment()->withParameters(1, $price);
        // Set the credit card holder information
        
        $creditCard->setHolder()->setBirthdate($birthday);

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.

        $creditCard->setHolder()->setPhone()->withParameters(
            $area_code,
            $phone_mobile
        );

        $creditCard->setHolder()->setBirthdate($birthday);
        $creditCard->setHolder()->setName($name); // Equals in Credit Card

        $creditCard->setHolder()->setDocument()->withParameters(
            'CPF',
            $cpf
        );

        try {
            //Get the crendentials and register the boleto payment
            $result = $creditCard->register(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );
            $order = OrderController::createOrder($reference);
            //echo "<pre>";
            //print_r($result);
            return $result->getCode();
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
