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

class creditCardCheckoutController extends Controller
{
    public static function creditCardCheckout(Request $request)
    {       
        //return gettype($request->checkoutData['cart']);
        
        $validator = Validator::make($request->checkoutData, [
            'name' => 'bail|required|present|filled',
            'SenderHash' => 'bail|required|present|filled',
            'creditCardToken' => 'bail|required|present|filled',
            'cpf' => 'bail|required|present|filled',                    
        ]);
        
        if($validator->fails()){
            return $validator->errors()->all();
        }
        // Instantiate a new payment request
        $directPaymentRequest = new PagSeguroDirectPaymentRequest();

        // Set the Payment Mode for this payment request
        $directPaymentRequest->setPaymentMode('DEFAULT');

        // Set the Payment Method for this payment request
        $directPaymentRequest->setPaymentMethod('CREDIT_CARD');

        /**
        * @todo Change the receiver Email
        */
        $directPaymentRequest->setReceiverEmail('danielrns15@gmail.com');

        // Set the currency
        $directPaymentRequest->setCurrency("BRL");

        // Add an item for this payment request
        $price = 0;


        $cart_products = CartController::loadCart();

        if(!is_array($cart_products))
            return response()->json(['error' => 'cart_is_empty'], 400); 
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
        
        $directPaymentRequest->setSenderHash($request->checkoutData['SenderHash']);

        // Set shipping information for this payment request
        $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');
        $directPaymentRequest->setShippingType($sedexCode);
        
        $address = userController::loadData();
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

        //Set billing information for credit card
        $state = State::getIsoCode($address['address'][0]['id_state']);
        $state =  $state[0]['iso_code'];

        $billingAddress = new PagSeguroBilling(  
            array(  
              'postalCode' => $address['address'][0]['postcode'],  
              'street' => strstr($address['address'][0]['address1'],',',true),  
              'number' => substr(strrchr($address['address'][0]['address1'],','),1),  
              'complement' => $address['address'][0]['other'],  
              'district' => $address['address'][0]['address2'],  
              'city' => $address['address'][0]['city'],  
              'state' => $state,  
              'country' => 'BRA'  
            )  
        );  
        
        $creditCardToken = $request->checkoutData['creditCardToken'];

        
        $installments = new PagSeguroDirectPaymentInstallment(  
          array(  
            'quantity' => 1,  
            'value' => $price,
            "noInterestInstallmentQuantity" => 2
          )  
        ); //return $price;

        function mask($val, $mask){
            $maskared = '';
            $k = 0;
            for($i = 0; $i<=strlen($mask)-1; $i++){
                if($mask[$i] == '#'){
                    if(isset($val[$k]))
                        $maskared .= $val[$k++];
                }
                else{
                    if(isset($mask[$i]))
                        $maskared .= $mask[$i];
                }
            }
        return $maskared;
        }

        $cpf = mask($request->checkoutData['cpf'],'###.###.###-##');
        $birthday = date("d/m/Y",strtotime($address['user'][0]['birthday']));

        $creditCardData = new PagSeguroCreditCardCheckout(
            array(
                'token' => $creditCardToken,
                'installment' => $installments,
                'billing' => $billingAddress,
                'holder' => new PagSeguroCreditCardHolder(
                    array(
                        'name' => $request->checkoutData['name'], //Equals in Credit Card
                        'documents' => array(
                            'type' => 'CPF',
                            'value' => $cpf
                        ),
                        'birthDate' => date($birthday),
                        'areaCode' => str_split($address['address'][0]['phone_mobile'] , 2)[0],
                        'number' => substr($address['address'][0]['phone_mobile'],2)
                    )
                )
            )
        );

        //Set credit card for payment
        $directPaymentRequest->setCreditCard($creditCardData);

        try {

            // application authentication
            $credentials = PagSeguroConfig::getAccountCredentials();
            // Register this payment request in PagSeguro to obtain the payment URL to redirect your customer.
            $order = OrderController::createOrder($cart_products);

            $response = $directPaymentRequest->register($credentials);

        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }
}
