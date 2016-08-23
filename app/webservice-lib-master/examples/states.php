<?php
define('DEBUG', false);											// Debug mode
define('PS_SHOP_PATH', 'http://127.0.1.1/prestashop');		// Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', 'DPPRE8CVJ4D4GAXIDVCBQ3LBI5CZT9GL');	// Auth key (Get it in your Back Office)
require_once('./PSWebServiceLibrary.php');

// Here we make the WebService Call
try
{
  $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  
  // Here we set the option array for the Webservice : we want customers resources
  //	$assoc = xmlDoc.getElementsByTagName("quantity");
  $opt = array(
    'resource' => 'states',
    'display'    => '[name,id]',
    'filter[id]' => '[313,339]'
   	
);
  $xml = $webService->get($opt);
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
if (isset($resources))
{ 

	foreach ($resources as $resource) {
   $states[$resource->id] = $resource->name;
	}  
  print_r($states);
}

?>