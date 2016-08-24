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
            'cart.*'=> 'bail|required|present|filled',
            'cart' => 'bail|required|present|filled',
            'userData' => 'bail|required|present|filled',
            'userData.phone' => 'present',
            'userData.address1' => 'bail|required|present',
            'userData.address2' => 'bail|required|present',
            'userData.city' => 'bail|required|present',
            'userData.state' => 'bail|required|present',
            'userData.postcode' => 'bail|required|present',
            'userData.firstname' => 'bail|required|present',
            'userData.lastname' => 'bail|required|present',
            'userData.phone_mobile' => 'bail|required|present',
            'userBirth.*'=> 'bail|required|present|filled',
            'userBirth'=> 'bail|required|present|filled',                       
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
        $quantity = 0;
        $count = count($request->checkoutData['cart']['items']);

        for($i=0;$i<$count;$i++){
            $directPaymentRequest->addItem(
                $request->checkoutData['cart']['items'][$i]['_id'],
                $request->checkoutData['cart']['items'][$i]['_name'].','.$request->checkoutData['cart']['items'][$i]['_data']['size'],
                $quantity = $request->checkoutData['cart']['items'][$i]['_quantity'],
                number_format($request->checkoutData['cart']['items'][$i]['_price'],2)
            );
            $price = $price + number_format($quantity * $request->checkoutData['cart']['items'][$i]['_price'],2, '.', '');
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
        
         if(isset($request->checkoutData['userData']['other'])) {
            $complement = $request->checkoutData['userData']['other'];
        }
        else{
            $complement = null;
        }


        $directPaymentRequest->setShippingAddress(
            $request->checkoutData['userData']['postcode'], //CEP
            strstr($request->checkoutData['userData']['address1'],',',true), //Logradouro
            substr(strrchr($request->checkoutData['userData']['address1'],','),1), //Numero
            null,//Complemento
            $request->checkoutData['userData']['address2'], //Bairro
            $request->checkoutData['userData']['city'], //Cidade
            $request->checkoutData['userData']['state'],//Estado
            'BRA'
        );

        $state = State::where('id_state',$request->checkoutData['userData']['id_state'])->select('iso_code')->get();
        $state =  $state[0]['iso_code'];
        //Set billing information for credit card




        $billingAddress = new PagSeguroBilling(  
            array(  
              'postalCode' => $request->checkoutData['userData']['postcode'],  
              'street' => strstr($request->checkoutData['userData']['address1'],',',true),  
              'number' => substr(strrchr($request->checkoutData['userData']['address1'],','),1),  
              'complement' => null,  
              'district' => $request->checkoutData['userData']['address2'],  
              'city' => $request->checkoutData['userData']['city'],  
              'state' => $state,  
              'country' => 'BRA'  
            )  
        );  
        
        $creditCardToken = $request->checkoutData['creditCardToken'];

        
        $installments = new PagSeguroDirectPaymentInstallment(  
          array(  
            'quantity' => 1,  
            'value' =>$price,
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
        $birthday = $newDate = date("d/m/Y", strtotime($request->checkoutData['userBirth']['birthday']));

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
                        'areaCode' => str_split($request->checkoutData['userData']['phone_mobile'], 2)[0],
                        
                        'number' => substr($request->checkoutData['userData']['phone_mobile'],2)
                    )
                )
            )
        );

        //Set credit card for payment
        $directPaymentRequest->setCreditCard($creditCardData);

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
            
            $response = $directPaymentRequest->register($credentials);

            //return $response;
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }
}