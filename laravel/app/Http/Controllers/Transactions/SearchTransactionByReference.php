<?php

namespace App\Http\Controllers\Transactions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PagSeguro\Library;
use App\Http\Requests;

class SearchTransactionByReference extends Controller
{
    public static function getTransaction($reference){
	

	\PagSeguro\Library::initialize();

    /*$options = [
    'initial_date' => '2016-04-01T14:55',
    'final_date' => '2016-04-24T09:55', //Optional
    'page' => 1, //Optional
    'max_per_page' => 20, //Optional
    ];*/

    $options = [
    'initial_date' => '2017-02-01T14:55',
    ];

    //$reference = "LIBPHP000001";

    try {
        $response = \PagSeguro\Services\Transactions\Search\Reference::search(
            \PagSeguro\Configuration\Configure::getAccountCredentials(),
            $reference,
            $options
        );

        echo "<pre>";
        //print_r(get_class_methods($response));
        print_r($response->getTransactions());
        //print_r(get_class_methods($teste[0]));
    } catch (Exception $e) {
        die($e->getMessage());
    }




    }
}
