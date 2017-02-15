<?php
namespace App\Http\Controllers\Checkout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Http\Requests;
use PagSeguro\Library;

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

    }
}