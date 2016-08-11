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
            $request->checkoutData['userData']['phone_mobile'],
            'CPF',
            $request->checkoutData['cpf'],
            true
        );
        
        $directPaymentRequest->setSenderHash($request->checkoutData['SenderHash']);

        // Set shipping information for this payment request
        $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');
        $directPaymentRequest->setShippingType($sedexCode);


        return $request->checkoutData['userData'];
        $directPaymentRequest->setShippingAddress(
            $request->checkoutData['userData']['postcode'],
            'Av. Brig. Faria Lima',
            '1384',
            $request->checkoutData['userData']['other'],
            $request->checkoutData['userData']['address2'],
            $request->checkoutData['userData']['state'],
            'SP',
            'BRA'
        );

        //Set billing information for credit card
        $billing = new PagSeguroBilling
        (
            array(
                'postalCode' => '01452002',
                'street' => 'Av. Brig. Faria Lima',
                'number' => '1384',
                'complement' => 'apto. 114',
                'district' => 'Jardim Paulistano',
                'city' => 'São Paulo',
                'state' => 'SP',
                'country' => 'BRA'
            )
        );

        $token = "5b97542cd1524b67a9e89b3d90c1f262";

        $installment = new PagSeguroDirectPaymentInstallment(
            array(
              "quantity" => 2,
              "value" => "15.00",
              "noInterestInstallmentQuantity" => 2
            )
        );

        $cardCheckout = new PagSeguroCreditCardCheckout(
            array(
                'token' => $token,
                'installment' => $installment,
                'holder' => new PagSeguroCreditCardHolder(
                    array(
                        'name' => 'João Comprador', //Equals in Credit Card
                        'documents' => array(
                            'type' => 'CPF',
                            'value' => '156.009.442-76'
                        ),
                        'birthDate' => date('01/10/1979'),
                        'areaCode' => 11,
                        'number' => 56273440
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
            $credentials = PagSeguroConfig::getApplicationCredentials();
            $credentials->setAuthorizationCode("E231B2C9BCC8474DA2E260B6C8CF60D3");

            // Register this payment request in PagSeguro to obtain the payment URL to redirect your customer.
            $return = $directPaymentRequest->register($credentials);

            self::printTransactionReturn($return);

        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }

    }

}