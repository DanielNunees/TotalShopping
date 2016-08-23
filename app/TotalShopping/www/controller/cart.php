<?php 
echo "Favorite color is " . $_SESSION['name'] . ".<br>";

class Cart{
	
	//public $name = $_SESSION["name"];
	//public $quantity = $_SESSION["quantity"];
	//public $value = $_SESSION["value"];
	//public $color = $_SESSION["color"];
	//public $size = $_SESSION["size"];
	//public $id = $_SESSION["id"];


	


public function update_cart($quantity){
	$this->quantity = $quantity;
}

public function __destruct() {
       print "Destruindo " . $this->name . "\n";
   }







}