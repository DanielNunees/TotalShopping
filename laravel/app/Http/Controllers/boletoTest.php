<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Tools;
use App\Http\Requests;
use App\Models\State;
use App\Http\Controllers\Transactions\SearchTransactionByReference;

class boletoTest extends Controller
{
    public function checkoutBoleto(){
	   
        //$id_customer = myAuthController::getAuthenticatedUser();
        $id_customer = 34;

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
