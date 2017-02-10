<?php

namespace App\Http\Controllers\Transactions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PagSeguro\Library;
use App\Http\Requests;

class SearchTransactionByCode extends Controller
{
    public function getTransaction(){
	

	\PagSeguro\Library::initialize();

    $code = '42EC87E30739446594389BA119FB3D73';

    try {
        $response = \PagSeguro\Services\Transactions\Search\Code::search(
            \PagSeguro\Configuration\Configure::getAccountCredentials(),
            $code
        );

        echo "<pre>";
        print_r($response);
    } catch (Exception $e) {
        die($e->getMessage());
    }



    }
}
