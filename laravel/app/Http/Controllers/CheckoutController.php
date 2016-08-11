<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



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

class CheckoutController extends Controller
{
	public function getSession(){
		try {
			$pagseguro = PagSeguroLibrary::init();  
      $credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials()
     	//$checkoutUrl = $paymentRequest->register($credentials);
			$sessionId = PagSeguroSessionService::getSession($credentials);
      return $sessionId;   
    	}catch (PagSeguroServiceException $e) {  
        	die($e->getMessage());  
    	}
	}

    public static function creditCardCheckout(Request $request)
    {
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
        //dd($request->checkoutData['cart']);
        

        $directPaymentRequest->addItem(
            $request->checkoutData['cart']['items'][0]['_id'],
            $request->checkoutData['cart']['items'][0]['_name'].','.$request->checkoutData['cart']['items'][0]['_data']['size'],
            $request->checkoutData['cart']['items'][0]['_quantity'],
            $request->checkoutData['cart']['items'][0]['_price']
        );

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $directPaymentRequest->setReference("REF123");

        // Set your customer information.
        // If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
        $directPaymentRequest->setSender(
            $request->checkoutData['userData']['firstname']." ".$request->checkoutData['userData']['lastname'],
            $request->checkoutData['userBirth']['email'],
            str_split($request->checkoutData['userData']['phone_mobile'], 2)[0],
            substr($request->checkoutData['userData']['phone_mobile'],2),
            'CPF',
            $request->checkoutData['cpf'],
            true
        );
        
        $directPaymentRequest->setSenderHash($request->checkoutData['SenderHash']);

        // Set shipping information for this payment request
        $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');
        $directPaymentRequest->setShippingType($sedexCode);


        

        $directPaymentRequest->setShippingAddress(
            $request->checkoutData['userData']['postcode'], //CEP
            strstr($request->checkoutData['userData']['address1'],',',true), //Logradouro
            substr(strrchr($request->checkoutData['userData']['address1'],','),1), //Numero
            $request->checkoutData['userData']['other'],//Complemento
            $request->checkoutData['userData']['address2'], //Bairro
            $request->checkoutData['userData']['state'], //Estado
            State::where('id_state',$request->checkoutData['userData']['id_state'])->select('iso_code')->get(),//Sigla do estado
            'BRA'
        );

        //Set billing information for credit card
        $billing = new PagSeguroBilling
        (
            array(
                'postalCode' => $request->checkoutData['userData']['postcode'],
                'street' => $request->checkoutData['userData']['address1'],
                'number' => substr(strrchr($request->checkoutData['userData']['address1'],','),1),
                'complement' => $request->checkoutData['userData']['other'],
                'district' => $request->checkoutData['userData']['address2'],
                'city' => $request->checkoutData['userData']['state'],
                'state' => State::where('id_state',$request->checkoutData['userData']['id_state'])->select('iso_code')->get(),
                'country' => 'BRA'
            )
        );
        
        $token = $request->checkoutData['creditCardToken'];

        $installment = new PagSeguroDirectPaymentInstallment(
            array(
              "quantity" => $request->checkoutData['cart']['items'][0]['_quantity'],
              "value" => $request->checkoutData['cart']['items'][0]['_price']
              //"noInterestInstallmentQuantity" => 2
            )
        );


        $cardCheckout = new PagSeguroCreditCardCheckout(
            array(
                'token' => $request->checkoutData['creditCardToken'],
                'installment' => $installment,
                'holder' => new PagSeguroCreditCardHolder(
                    array(
                        'name' => $request->checkoutData['userData']['firstname'].' '.$request->checkoutData['userData']['lastname'], //Equals in Credit Card
                        'documents' => array(
                            'type' => 'CPF',
                            'value' => $request->checkoutData['cpf']
                        ),
                        'birthDate' => date($request->checkoutData['userBirth']['birthday']),
                        'areaCode' => str_split($request->checkoutData['userData']['phone_mobile'], 2)[0],
                        'number' => substr($request->checkoutData['userData']['phone_mobile'],2)
                    )
                ),
                'billing' => $billing
            )
        );

        //Set credit card for payment
        $directPaymentRequest->setCreditCard($cardCheckout);

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
            return $response;
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    

        

    }

}