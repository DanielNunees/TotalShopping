<?php

namespace App\Http\Controllers\Transactions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PagSeguro\Library;
use App\Http\Requests;

class TransactionController extends Controller
{
    public function getUserTransactionsStatus($reference){
        
        $id_customer = myAuthController::getAuthenticatedUser();
        if(!is_int($id_customer)){
            return $id_customer;
        }
        $order =  OrderController::getOrderByCustomerId($id_customer);

        foreach ($order as $key => $value) {
          $reference = $value->reference;
        }
        SearchTransactionByReference::getTransaction($reference);
        //return $order;
    }
}
