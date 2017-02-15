<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class InstallmentsController extends Controller
{
    public function getInstallments($amount){
    	\PagSeguro\Library::initialize();
		\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
		\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

		$options = [
		    'amount' => $amount, //Required
		    'card_brand' => 'visa', //optional
		    'max_installment_no_interest' => 2 //optional
		];

		try {
		    $result = \PagSeguro\Services\Installment::create(
		        \PagSeguro\Configuration\Configure::getAccountCredentials(),
		        $options
		    );

		    echo "<pre>";
		    print_r($result->getInstallments());
		} catch (Exception $e) {
		    die($e->getMessage());
		}
    }
}
