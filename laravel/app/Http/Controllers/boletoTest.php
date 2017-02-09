<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class boletoTest extends Controller
{
    public function checkoutBoleto(){

		\PagSeguro\Library::initialize();
		\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
		\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");

		//Instantiate a new Boleto Object
		$boleto = new \PagSeguro\Domains\Requests\DirectPayment\Boleto();

		// Set the Payment Mode for this payment request
		$boleto->setMode('DEFAULT');

		/**
		 * @todo Change the receiver Email
		 */
		//$boleto->setReceiverEmail('vendedor@lojamodelo.com.br'); 

		// Set the currency
		$boleto->setCurrency("BRL");

		// Add an item for this payment request
		$boleto->addItems()->withParameters(
		    '0001',
		    'Notebook prata',
		    2,
		    130.00
		);

		// Add an item for this payment request
		$boleto->addItems()->withParameters(
		    '0002',
		    'Notebook preto',
		    2,
		    430.00
		);

		// Set a reference code for this payment request. It is useful to identify this payment
		// in future notifications.
		$boleto->setReference("LIBPHP000001-boleto");

		//set extra amount
		$boleto->setExtraAmount(11.5);

		// Set your customer information.
		// If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
		$boleto->setSender()->setName('João Comprador');
		$boleto->setSender()->setEmail('v30781034923906770092@sandbox.pagseguro.com.br');

		$boleto->setSender()->setPhone()->withParameters(
		    11,
		    56273440
		);

		$boleto->setSender()->setDocument()->withParameters(
		    'CPF',
		    '156.009.442-76'
		);

		$boleto->setSender()->setHash('e2f5afd02100ccf2ee8274aa86b4a864f2bab4521719027eac5b1fceda90a527');

		$boleto->setSender()->setIp('127.0.0.0');

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

		    //
		    $link = new \PagSeguro\Parsers\Transaction\Boleto\Response;

		    echo $link->getPaymentLink();

		    return ($result->getPaymentLink());
		    //echo "<pre>";
		    //print_r($result);
		} catch (Exception $e) {
		    echo "</br> <strong>";
		    die($e->getMessage());
		}

    }
}
