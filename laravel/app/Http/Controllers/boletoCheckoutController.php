<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Http\Requests;
use App\Libraries\PagSeguroLibrary\PagSeguroLibrary;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroPaymentRequest;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroBilling;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroDirectPaymentInstallment;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroCreditCardHolder;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroShippingType;
use App\Libraries\PagSeguroLibrary\config\PagSeguroConfig;
use App\Libraries\PagSeguroLibrary\service\PagSeguroSessionService;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroDirectPaymentRequest;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroInstallment;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroCreditCardCheckout;

use App\Http\Controllers\OrderController;
use App\Models\State;
use Exception;

class boletoCheckoutController extends Controller
{
    public static function boletoCheckout(Request $request)
    {       
    
        // Instantiate a new payment request
        $directPaymentRequest = new PagSeguroDirectPaymentRequest();

        // Set the Payment Mode for this payment request
        $directPaymentRequest->setPaymentMode('DEFAULT');

        // Set the Payment Method for this payment request
        $directPaymentRequest->setPaymentMethod('BOLETO');

        /**
        * @todo Change the receiver Email
        */
        $directPaymentRequest->setReceiverEmail('danielrns15@gmail.com');

        // Set the currency
        $directPaymentRequest->setCurrency("BRL");

        // Add an item for this payment request

        $cart_products = CartController::loadCart();
        $price =0;
        //return $cart_products;
        foreach ($cart_products as $key => $value) {
            $directPaymentRequest->addItem(
                $value['product']['description'][0]['id_product'],
                $value['product']['description'][0]['name'].','.$value['product']['attributes'][0]['name'],
                $quantity = $value['quantity'] ,
                number_format($value['product']['description'][0]['product_price']['price'] ,2)
            );
            $price = $price + number_format($quantity * $value['product']['description'][0]['product_price']['price'],2, '.', '');
        }

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $directPaymentRequest->setReference("REF123");

        // Set your customer information.
        // If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
        $directPaymentRequest->setSender(
            'Daniel Nunes',
            'v30781034923906770092@sandbox.pagseguro.com.br',
            '27',
            '999887766',
            'CPF',
            '156.009.442-76',
            true
        );
        
        $directPaymentRequest->setSenderHash($request->SenderHash);

        // Set shipping information for this payment request
        $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');
        $directPaymentRequest->setShippingType($sedexCode);

        $address = userController::loadData();
        //return $address['address'];
        $directPaymentRequest->setShippingAddress(
            $address['address'][0]['postcode'], //CEP
            strstr($address['address'][0]['address1'],',',true), //Logradouro
            substr(strrchr($address['address'][0]['address1'],','),1), //Numero
            $address['address'][0]['other'],//Complemento
            $address['address'][0]['address2'], //Bairro
            $address['address'][0]['city'], //Cidade
            $address['address'][0]['state'],//Estado
            'BRA'
        );

        
        try {
            /**
             * #### Credentials #####
             * Replace the parameters below with your credentials
             * You can also get your credentials from a config file. See an example:
             * $credentials = PagSeguroConfig::getAccountCredentials();
             */

            // seller authentication
            //$credentials = new PagSeguroAccountCredentials("vendedor@lojamodelo.com.br",
            //    "E231B2C9BCC8474DA2E260B6C8CF60D3");

            // application authentication
            $credentials = PagSeguroConfig::getAccountCredentials();

            // Register this payment request in PagSeguro to obtain the payment URL to redirect your customer.
            $order = OrderController::createOrder();
            //$response = $directPaymentRequest->register($credentials);
            return $order;
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }
}
