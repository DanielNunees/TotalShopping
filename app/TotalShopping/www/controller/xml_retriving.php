<?php
define('DEBUG', false);// Debug mode
define('PS_SHOP_PATH', 'http://127.0.1.1/prestashop');		// Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', 'DPPRE8CVJ4D4GAXIDVCBQ3LBI5CZT9GL');	// Auth key (Get it in your Back Office)
require_once('PSWebServiceLibrary.php');


// Here we make the WebService Call

try
{
  if(isset($_GET["categoria"])) {$categoria = $_GET["categoria"];}
  $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  if(isset($categoria)){
    $categorie = array(
    'resource' => 'categories',
    'display'  => 'full',
    'filter[id]' => $categoria
    );
  }else{
    $categorie = array(
    'resource' => 'categories',
    'display'  => 'full'
    );
  }
    $product = array(
    'resource' => 'products',
    'display'  => 'full'
    );
 
  $xml = $webService->get($categorie);
  $categories = $xml->children()->children();

  $xml1 = $webService->get($product);
  $products1 = $xml1->children()->children();
}
catch (PrestaShopWebserviceException $e)
{
  // Here we are dealing with errors
  $trace = $e->getTrace();
  if ($trace[0]['args'][0] == 404) echo 'Bad ID';
  else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
  else echo 'Other error: ' , $e->getMessage(),"\n";
}
if (isset($products1))
{   
    $resource = array();
    foreach ($products1 as $resource)
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
      $products_list[] = array('name' => $name, 'image' => $image, 'id' => $id, 'description' => $description, 'path' => $path, 'price'=>$price);
    }
}

?>

