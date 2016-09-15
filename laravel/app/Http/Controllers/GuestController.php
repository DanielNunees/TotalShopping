<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use App\Http\Requests;
use App\Tools\Tools;

class GuestController extends Controller
{
    public static function newGuest($id_customer){
    	$id_operating_system = Tools::ConfereSO();
    	$new_guest = new Guest;
    	$new_guest = $new_guest->insertGetId([
    		'id_operating_system'=>$id_operating_system,
    		'id_web_browser'=>3,
    		'id_customer'=>$id_customer,
    		'javascript'=>0,
    		'screen_resolution_x'=>0,
    		'screen_resolution_y'=>0,
    		'screen_color'=>0,
    		'sun_java'=>0,
    		'adobe_flash'=>0,
    		'adobe_director'=>0,
    		'apple_quicktime'=>0,
    		'real_player'=>0,
    		'windows_media'=>0,
    		'accept_language'=>'pt',
    		'mobile_theme'=>0]);
    	return $new_guest;
    }
    
}
