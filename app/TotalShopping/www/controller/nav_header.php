<?php 
require_once('../controller/all_departaments.php');
define("SERVER", "http://".$_SERVER['SERVER_NAME']);
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once('header.php'); ?>
</head>
<body>
  <div data-role="page">
    <header data-role="header">

      <?php if(isset($_GET["id"])){$id = $_GET["id"];}else{ ?>
      <h3>All departaments ID error!</h3>
      <?php }?>
      <?php  if(isset($products)) {  ?>
      <?php $cate=0; foreach($products as $product){ ?>
      <div data-role="navbar">
        <ul>
          <li><a href="<?php echo SERVER '/app/view/departament.php?id='.$id.'&categoria='.$categories_id[$cate]?>"><?php echo $product['name']; ?></a></li>
        </ul>
      </div>
      <div class="container home-color"  >
            <a class="nounderline " href="<?php echo SERVER.'/app/view/departament.php?id='.$id.'&categoria='.$categories_id[$cate]?>">
              <!--img  src="http://127.0.1.1/app/images/departament/female_shoes.jpg" class="img-circle" width="50" -->
              <b><h3 style="display: inline; color:black;">
              </h3></b><h4 style=" margin: 0 ">Mais</h4>
            </a>       
      </div>

      <?php $cate++;}  ?>
      <?php }else{ ?>
      <h4>Sem produtos nesse departamento! </h4>
      <a href="<?php echo SERVER.'/app/view/home.php'?>">
        <button class="btn-primary">Voltar</button>
      </a>
      <?php }?>
    </section>





    <?php require_once('nav.php'); ?>
  </div>
</body>
</html>