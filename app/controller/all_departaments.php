<?php
require_once('xml_retriving.php');
if (isset($categories) && !isset($categoria)) {
  $v =0; 
	$id = $_GET["id"];
  foreach ($categories as $resource) {
    if($resource->id==$id){
      while (isset($resource->associations->categories->category[$v])) {
        $categories_id[] = (int) $resource->associations->categories->category[$v]->id;
        $v++;
      }
    }
  }

/* categorias */


  if(isset($categories_id)){
      foreach ($categories as $resource) {
        $v=0;
        if($categories_id[array_search($resource->id, $categories_id)] == $resource->id){
          $nameLanguage = $resource->xpath('name/language[@id=2]');
          $name = (string) $nameLanguage[0];
          unset($produto);
          while (isset($resource->associations->products->product[$v])) {
            $produto[$v] = $products_list[array_search($resource->associations->products->product[$v]->id, array_column($products_list, 'id'))];
            $v++;
           }
           $produtos[] = $produto;
           $products[] = array('name' => $name,'produtos' => $produto);        
        } 
      }   
  }
}
//print_r(array_chunk($produtos,2));


/* categorias id especifico*/

if(!empty($categories)){
if(isset($categoria)){
  $v=0;
  $nameLanguage1 = $categories->xpath('name/language[@id=2]');
  $name1 = (string) $nameLanguage1[0];
  //unset($all_ids);
  while(isset($categories->category->associations->products->product[$v])) {  
    $aux = $categories->category->associations->products->product[$v]->id;
    $v++;
  } 
  $products[] = array('name' => $name1);      
}


 

}

//print_r(array_chunk($produtos,3));
?>