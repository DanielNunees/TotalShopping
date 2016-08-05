<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Libraries\PagSeguroLibrary\PagSeguroLibrary;
use App\Libraries\PagSeguroLibrary\domain\PagSeguroPaymentRequest;
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

    public function checkout(Request $request){

    	//$pagseguro = PagSeguroLibrary::init();
    	//$paymentRequest = new PagSeguroPaymentRequest();
    	$directPaymentRequest = new PagSeguroDirectPaymentRequest();
    	$directPaymentRequest->setPaymentMode('DEFAULT'); // GATEWAY  
		$directPaymentRequest->setPaymentMethod('CREDIT_CARD');      	
    }
}