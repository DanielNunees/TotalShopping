<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Http\Requests;

use App\Http\Controllers\OrderController;
use App\Models\State;
use Exception;

class boletoController extends Controller
{
    public static function boleto(Request $request)
    { 
        //Instantiate a new Boleto Object
        $boleto = new \PagSeguro\Domains\Requests\DirectPayment\Boleto();

        // Set the Payment Mode for this payment request
        $boleto->setMode('DEFAULT');

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
        //return $cart_products;
        foreach ($cart_products as $key => $value) {
            $boleto->addItems()->withParameters(
                $value['product']['description'][0]['id_product'],
                $value['product']['description'][0]['name'].','.$value['product']['attributes'][0]['name'],
                $quantity = $value['quantity'] ,
                number_format($value['product']['description'][0]['product_price']['price'] ,2)
            );
            $price = $price + number_format($quantity * $value['product']['description'][0]['product_price']['price'],2, '.', '');
        }

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $boleto->setReference("REF123-boleto");

        //set extra amount
        //$boleto->setExtraAmount(11.5);

        // Set your customer information.
        // If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
        $boleto->setSender()->setName('Daniel Nunes');
        $boleto->setSender()->setEmail('v30781034923906770092@sandbox.pagseguro.com.br');

        $boleto->setSender()->setPhone()->withParameters(
            27,
            999887766
        );

        $boleto->setSender()->setDocument()->withParameters(
            'CPF',
            '156.009.442-71'
        );

        $boleto->setSender()->setHash($request->checkoutData['SenderHash']);

        $boleto->setSender()->setIp('127.0.0.0');



        $address = userController::loadData();
        //return $address['address'];
        $boleto->setShipping()->setAddress()->withParameters(
            strstr($address['address'][0]['address1'],',',true), //Logradouro
            substr(strrchr($address['address'][0]['address1'],','),1), //Numero
            $address['address'][0]['address2'], //Bairro
            $address['address'][0]['postcode'], //CEP
            $address['address'][0]['city'], //Cidade
            $address['address'][0]['state'],//Estado
            'BRA'
            $address['address'][0]['other'],//Complemento
        );

        // Set shipping information for this payment request
        $boleto->setShipping()->setAddress()->withParameters(
            'Av. Brig. Faria Lima',
            '1384',
            'Jardim Paulistano',
            '01452002',
            'São Paulo',
            'SP',
            'BRA',
            'apto. 114'
        );

        try {
            //Get the crendentials and register the boleto payment
            $result = $boleto->register(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );

            echo "<pre>";
            print_r($result);
        } catch (Exception $e) {
            echo "</br> <strong>";
            die($e->getMessage());
        }
    }
}
