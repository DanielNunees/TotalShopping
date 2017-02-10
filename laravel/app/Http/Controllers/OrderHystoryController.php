<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Order;
use App\Http\Requests;
use App\Models\Order\OrderHistory;

class OrderHystoryController extends Controller
{
    public static function newHistory($order_id){
    	try {
    		$id_customer = myAuthController::getAuthenticatedUser();
    	} catch (Exception $e) {
    		return $e;
    	}

    	$today = date("Y-m-d H:i:s");
    	$history = ['id_employee'=>1, //ps_employee TODO
    				'id_order'=>$order_id,
    				'id_order_state'=>15,
    				'date_add'=> $today];
    	
    	return OrderHistory::setHistory($history);

    	/*  id_order_state = 15 -> pagseguro(iniciado) [pagseguro-sdk: <status> =>1]
		/*	id_order_state = 16 -> pagseguro(Aguardando pagamento) [pagseguro-sdk: <status> =>1]
		/*	id_order_state = 17 -> pagseguro(Em analise) [pagseguro-sdk: <status> =>2]
		/*	id_order_state = 18 -> pagseguro(Paga) [pagseguro-sdk: <status> =>3]
		/*	id_order_state = 19 -> pagseguro(Disponivel) [pagseguro-sdk: <status> =>4]
    	/*	id_order_state = 20 -> pagseguro(Em disputa) [pagseguro-sdk: <status> =>5]
    	/*	id_order_state = 21 -> pagseguro(Devolvida) [pagseguro-sdk: <status> =>6]
    	/*	id_order_state = 22 -> pagseguro(Cancelada) [pagseguro-sdk: <status> =>7]
    	*/	

    }

    public static function updateHistory(){
    	//TODO
    }
}
