<?php
define('DEBUG', false);											// Debug mode
define('PS_SHOP_PATH', 'http://127.0.1.1/prestashop');		// Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', 'DPPRE8CVJ4D4GAXIDVCBQ3LBI5CZT9GL');	// Auth key (Get it in your Back Office)
require_once('./PSWebServiceLibrary.php');

// Here we make the WebService Call

try
{
  $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);

  $opt = array(
    'resource' => 'customers',
    'display' => 'full',
    'display'  => '[email]',
    'filter[email]' => $email = $_POST['email']
    
);
  $customer = $webService->get($opt);
  $customers = $customer->children()->children();

  $xml = $webService->get(array('url' => 'http://127.0.1.1/prestashop/api/customers?schema=synopsis'));
  $resources = $xml->children()->children();

}
catch (PrestaShopWebserviceException $e)
{
  // Here we are dealing with errors
  $trace = $e->getTrace();
  if ($trace[0]['args'][0] == 404) echo 'Bad ID';
  else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
  else echo 'Other error: ' , $e->getMessage(),"\n";
}
if(isset($resources)){

          if(isset($customers->customer->email))
            echo 'Email já cadastrado!';
          else{

            foreach ($resources as $nodeKey => $node) {
              if(isset($_POST[$nodeKey]))
                $resources->$nodeKey = $_POST[$nodeKey];
            }
          
 
            $opt = array('resource' => 'customers');
            $opt['postXml'] = $xml->asXML();
            $xml = $webService->add($opt);
            $id['customer'] = $xml->customer->id;

            
            $xml = $webService->get(array('url' => PS_SHOP_PATH.'/api/addresses?schema=synopsis'));
            $xml->address->id_customer = $id['customer'];
            $xml->address->firstname = $_POST['firstname'];
            $xml->address->lastname = $_POST['lastname'];
            $xml->address->id_state = $_POST['id_state'];
            $xml->address->address1 = $_POST['address1'];
            $xml->address->address2 = $_POST['address2'];
            $xml->address->city = $_POST['city'];
            $xml->address->phone_mobile = $_POST['phone_mobile'];
            $xml->address->id_country = '58';
            $xml->address->alias = '-';
 
            $opt = array('resource' => 'addresses');
            $opt['postXml'] = $xml->asXML();
            $xml = $webService->add($opt);
          }
          

}

?>

