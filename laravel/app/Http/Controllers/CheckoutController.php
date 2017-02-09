<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Http\Requests;
use PagSeguro\Library;

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
		Library::initialize();
        Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
        Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

        try {
            $sessionCode = \PagSeguro\Services\Session::create(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );

            return $sessionCode->getResult();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        /*try {
            $teste = Library::initialize();
			$pagseguro = PagSeguroLibrary::init();  
            $credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials()
            //$checkoutUrl = $paymentRequest->register($credentials);
        	$sessionId = PagSeguroSessionService::getSession($credentials);
            return $sessionId;   
    	}catch (PagSeguroServiceException $e) {  
        	die($e->getMessage());  
    	}
	}*/
}
}