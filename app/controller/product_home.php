<?php
define('DEBUG', false);                      // Debug mode
define('PS_SHOP_PATH', 'http://127.0.1.1/prestashop');    // Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', 'DPPRE8CVJ4D4GAXIDVCBQ3LBI5CZT9GL'); // Auth key (Get it in your Back Office)
require_once('PSWebServiceLibrary.php');

// Here we make the WebService Call
try
{
  $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  if(isset($_GET["id"])){
    $id = $_GET["id"];
  }
  else{
    $id = NULL;
  }
  // Here we set the option array for the Webservice : we want customers resources
  if(isset($id)){
    $opt = array(
    'resource' => 'products',
    'display'  => '[name,id_default_image,price,id,description]',
    'filter[id]' => $id
    );
  }else{
    $opt = array(
      'resource' => 'products',
      'display'  => '[name,id_default_image,price,id,description]'
      );
  }
  
  // Call
  $xml = $webService->get($opt);

  // Here we get the elements from children of customers markup "customer"
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

// if $resources is set we can lists element in it otherwise do nothing cause there's an error
if (isset($resources))
{   
    $resource = array();
    foreach ($resources as $resource)
    {
      // Iterates on the found IDs
      $nameLanguage = $resource->xpath('name/language[@id=1]');
      $name = (string) $nameLanguage[0];
      $idImage = (string) $resource->id_default_image;
      $image = '/img/p/';
      $price = $resource->price;
      for ($i = 0; $i < strlen($idImage); $i++) {
        $image .= $idImage[$i] . '/';
      }
      $image .= $idImage . '.jpg';
      $id = (int) $resource->id;
      $descriptionLanguage = $resource->xpath('description/language[@id=1]');
      $description = (string) $descriptionLanguage[0];
      $path = '/index.php?controller=product&id_product=' . $resource->id;
      $products[] = array('name' => $name, 'image' => $image, 'id' => $id, 'description' => $description, 'path' => $path, 'price'=>$price);
    }

}