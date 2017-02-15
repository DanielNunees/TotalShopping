<?php

namespace App\Tools;
use App\Tools\Tools;

class Tools{

    public static function ConfereSO(){

       if(strstr($_SERVER['HTTP_USER_AGENT'], 'Linux')) { 
            return 6; // Linux
       }
       elseif(strstr($_SERVER['HTTP_USER_AGENT'], 'Windows')) {    
            return 3; //Windows
       }
       else{
            return 0; //Nao identificado
       }
    }

	public static function passwdGen($length = 8, $flag = 'ALPHANUMERIC')
    {
        $length = (int)$length;
        if ($length <= 0) {
            return false;
        }
        switch ($flag) {
            case 'NUMERIC':
                $str = '0123456789';
                break;
            case 'NO_NUMERIC':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'RANDOM':
                $num_bytes = ceil($length * 0.75);
                $bytes = self::getBytes($num_bytes);
                return substr(rtrim(base64_encode($bytes), '='), 0, $length);
            case 'ALPHANUMERIC':
            default:
                $str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }
        $bytes = Tools::getBytes($length);
        $position = 0;
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $position = ($position + ord($bytes[$i])) % strlen($str);
            $result .= $str[$position];
        }
        return $result;
    }

    public static function unique_multidim_array($array, $key) { 
        $temp_array = []; 
        $i = 0; 
        $key_array = array(); 
        foreach($array as $val) {

            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $temp_array[] = ($val); 
            } 
            $i++; 
        }
        return ($temp_array); 
    }

    public static function CPFmask($val, $mask){
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++){
            if($mask[$i] == '#'){
                if(isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else{
                if(isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
    return $maskared;
    }

    public static function getBytes($length)
    {
        $length = (int)$length;
        if ($length <= 0) {
            return false;
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $crypto_strong);
            if ($crypto_strong === true) {
                return $bytes;
            }
        }
        if (function_exists('mcrypt_create_iv')) {
            $bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if ($bytes !== false && strlen($bytes) === $length) {
                return $bytes;
            }
        }
        // Else try to get $length bytes of entropy.
        // Thanks to Zend
        $result         = '';
        $entropy        = '';
        $msec_per_round = 400;
        $bits_per_round = 2;
        $total          = $length;
        $hash_length    = 20;
        while (strlen($result) < $length) {
            $bytes  = ($total > $hash_length) ? $hash_length : $total;
            $total -= $bytes;
            for ($i=1; $i < 3; $i++) {
                $t1 = microtime(true);
                $seed = mt_rand();
                for ($j=1; $j < 50; $j++) {
                    $seed = sha1($seed);
                }
                $t2 = microtime(true);
                $entropy .= $t1 . $t2;
            }
            $div = (int) (($t2 - $t1) * 1000000);
            if ($div <= 0) {
                $div = 400;
            }
            $rounds = (int) ($msec_per_round * 50 / $div);
            $iter = $bytes * (int) (ceil(8 / $bits_per_round));
            for ($i = 0; $i < $iter; $i ++) {
                $t1 = microtime();
                $seed = sha1(mt_rand());
                for ($j = 0; $j < $rounds; $j++) {
                    $seed = sha1($seed);
                }
                $t2 = microtime();
                $entropy .= $t1 . $t2;
            }
            $result .= sha1($entropy, true);
        }
        return substr($result, 0, $length);
    }
}