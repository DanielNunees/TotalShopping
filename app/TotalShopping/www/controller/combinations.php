<?php
define('DEBUG', false);                      // Debug mode
define('PS_SHOP_PATH', 'http://127.0.1.1/prestashop');    // Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', 'DPPRE8CVJ4D4GAXIDVCBQ3LBI5CZT9GL'); // Auth key (Get it in your Back Office)
require_once('PSWebServiceLibrary.php');
define("SERVER", "http://".$_SERVER['SERVER_NAME']);

// Here we make the WebService Call
try{
  $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
  
  // Here we set the option array for the Webservice : we want customers resources
  $stock = array(
    'resource' => 'stock_availables',
    'display' => '[id_product_attribute,quantity]',
    'filter[id_product]' => $id = $_GET["id"] 
);
  $opt = array(
    'resource' => 'products',
    'display'  => '[name,id_default_image,price,id,description]',
    'filter[id]' => $id = $_GET["id"]
  );
  $combinations = array(
    'resource' => 'combinations',
    'display' => 'full',
    'filter[id_product]' => $id = $_GET["id"]
  );
  $options_values = array(
      'resource' => 'product_option_values',
      'display'  => '[name,id,id_attribute_group]'
  );
  $xml_options_values = $webService->get($options_values);
  $xml = $webService->get($opt);
  $xml_combinations = $webService->get($combinations);
  $xml_stock = $webService->get($stock);

  $values = $xml_options_values->children()->children();
  $resources = $xml->children()->children();
  $combination = $xml_combinations->children()->children();
  $stock = $xml_stock->children()->children();
}
catch (PrestaShopWebserviceException $e)
{
  // Here we are dealing with errors
  $trace = $e->getTrace();
  if ($trace[0]['args'][0] == 404) echo 'Bad ID';
  else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
  else echo 'Other error: ' , $e->getMessage(),"\n";
}
$fine=true;
if (isset($resources))
{ 
  /* BUSCA AS INFORMAÇÕES ESSENCIAIS DO PRODUTO, NOME,IMAGEM,PREÇO,ID E DESCRIÇÃO, TODOS OS DADOS SÃO*/
  /* INSERIDOS EM UM VETOR, $products[] */
    $resource = array();
    foreach ($resources as $resource){
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
      $descriptionLanguage = $resource->xpath('description/language[@id=2]');
      $description = (string) $descriptionLanguage[0];
      $path = '/index.php?controller=product&id_product=' . $resource->id;
      $products[] = array('name' => $name, 'image' => $image, 'id' => $id, 'description' => $description, 'path' => $path, 'price'=>$price);
    }

    /* INSERE EM UM VETOR ASSOCIADO AO PROPRIO ID CADA NOME DE CADA OPCAO DISPONIVEL ID->OPTION*/
    foreach ($values as $value) {
      $nameLanguage = $value->xpath('name/language[@id=2]');
      $name = (string) $nameLanguage[0];
      $id = (int) $value->id;
      $group[$id] =(int) $value->id_attribute_group;
      $product_values[$id] = $name;
    }
    /* FAZ A BUSCA DAS COMBINAÇÕES QUE ESTEJAM DISPONIVEIS NO STOCK*/
    foreach ($stock as $stk) {
      if((int)$stk->quantity != 0 && $stk->id_product_attribute!=0){
        $combinations_in_stock[] = (int)$stk->id_product_attribute;
      }
    }

  /* ASSOCIA CADA COMBINAÇÃO COM SEUS RESPETIVOS ATRIBUTOS E INSERE EM UM UNICO VETOR
    $final[] = { [0] -> product option value 1 
                 [1] -> product option value 0 (Se existir)
                 [2] -> combianation id
                 [3] -> photos
    }
  */
  $aux = array();
  $a=0;$s=0;
  foreach ($combination as $combs) {
    $j=0;
    if(in_array($combs->id, $combinations_in_stock)){
      $pics = array();
          while (isset($combs->associations->images->image[$j])) {
            $img_id2 =  (string)$combs->associations->images->image[$j]->id;
            $image2 = '/img/p/';
            for ($i = 0; $i < strlen($img_id2); $i++) {
              $image2 .= $img_id2[$i] . '/';
            }
            $image2 .= $img_id2.'.jpg';
            if(in_array($image2, $pics)==false)
              $pics[] = $image2;
            $j++;
          }
      if($combs->associations->product_option_values->product_option_value[1]){
        $two_options = true;
        $key = array_search((int)$combs->associations->product_option_values->product_option_value[1]->id, $aux);
        if(!in_array((int)$combs->associations->product_option_values->product_option_value[1]->id, $aux)){
          $elementos = array($product_values[(int)$combs->associations->product_option_values->product_option_value[1]->id],$product_values[(int)$combs->associations->product_option_values->product_option_value[0]->id],(int)$combs->id,"photos" => $pics);
          array_push($aux,(int)$combs->associations->product_option_values->product_option_value[1]->id);
          $final[$a] = $elementos;
          $a++;
          unset($pics);          
        }else{
          array_push($final[$key],$product_values[(int)$combs->associations->product_option_values->product_option_value[0]->id],(int)$combs->id);
        }
      }else{
        $two_options = false;
        $elementos = array($product_values[(int)$combs->associations->product_option_values->product_option_value[0]->id],$product_values[(int)$combs->associations->product_option_values->product_option_value[0]->id],(int)$combs->id,"photos" => $pics);
        $final[$a] = $elementos;
        $a++;
      }       
    }
    $s++;
  }
    $final = array_reverse($final);
    // Password to be encrypted for a .htpasswd file


// Print encrypted password

}