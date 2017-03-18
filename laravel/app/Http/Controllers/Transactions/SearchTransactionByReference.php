<?php

namespace App\Http\Controllers\Transactions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PagSeguro\Library;
use App\Http\Requests;

class SearchTransactionByReference extends Controller
{
    public static function getTransactionStatus(Request $request){
        $reference = $request['reference'];
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

        try {
            $response = \PagSeguro\Services\Transactions\Search\Reference::search(
                \PagSeguro\Configuration\Configure::getAccountCredentials(),
                $reference,
                $options
            );

            $teste = $response->getTransactions();
            //print_r(get_class_methods($response));
            //print_r(get_class_methods($teste[0]));
            return $teste[0]->getStatus();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public static function getTransactionType(Request $request){
        $reference = $request['reference'];
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

        try {
            $response = \PagSeguro\Services\Transactions\Search\Reference::search(
                \PagSeguro\Configuration\Configure::getAccountCredentials(),
                $reference,
                $options
            );

            $teste = $response->getTransactions();
            //print_r(get_class_methods($response));
            //print_r(get_class_methods($teste[0]));
            return $teste[0]->getType();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
