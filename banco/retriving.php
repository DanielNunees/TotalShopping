
	<?php
		require 'configuration.php';
		require 'connection.php';
		require 'database.php';
		DBConnect();

$description = DBRead('product_lang',null, 'name');
//print_r($description);
$price = DBRead('product',null, 'price');

$images_id = DBRead('image','WHERE cover = 1', 'id_image');
//print_r($price);
//$result = array_merge_recursive($description,$price);
//$result = json_encode($result);

	foreach ($description as $key => $value) {
		$name[] = $value['name'];
	}

	foreach ($price as $key => $value) {
		$values[] = $value['price'];
	}


	foreach ($images_id as $key => $value) {	
 	  $idImage = (string) $value['id_image'];
      $image = '/img/p/';
      for ($i = 0; $i < strlen($idImage); $i++) {
        $image .= $idImage[$i] . '/';
      }
      $image .= $idImage . '.jpg';
      $img[] = $image;
      //echo "<img src='http://127.0.1.1/prestashop$image'>";
	}

	?>