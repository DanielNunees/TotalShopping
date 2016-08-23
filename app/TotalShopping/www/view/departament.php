<?php require_once('../controller/all_departaments.php'); ?>
<!DOCTYPE html>
<html>
<head>
<?php require_once('head.php'); ?>
</head>
<body>
  <div data-role="page">
    <section>
    <a data-rel="back" class="ui-btn ui-corner-all ui-icon-arrow-l ui-btn-icon-left">Voltar</a>
    
     <?php if(isset($_GET["id"])&&isset($_GET["categoria"])){$id = $_GET["id"]; $categoria = $_GET["categoria"];}else{ ?>
      <h3>Departament ID error!</h3>
      <?php }?>
      <?php  if(isset($produtos)) {  ?>
      <div class="panel container home-color" >
        <div class="row">
                  <?php foreach($produtos as $produto) {
                  $parcel=10; ?>
                    <div class="col-xs-6 col-sm-4" style="padding-right: 2px; padding-left: 2px">
                      <div class="thumbnail box-shadow--2dp"  >
                        <a class="nounderline" href="<?php echo 'http://192.168.1.4/app/view/product.php?id='.$produto['id'];?>" data-transition="slide" >
                          <?php if(isset($produto['image'])){ ?>
                            <img src="<?php echo 'http://192.168.1.4/prestashop'.$produto['image'];?>">
                          <?php } else{?>
                            <img src="http://192.168.1.4/prestashop/img/p/br-default-thickbox_default.jpg">
                          <?php } ?>
                            <div class="caption">
                              <small><?php echo substr_replace($produto['name'], (strlen($produto['name']) > 11 ? '...' : ''), 11);?></small>
                              <p><b>R$ <?php echo number_format((double)$produto['price'], 2, ',', '.'); ;?><br>
                              <?php
                                  while(bcdiv($produto['price'], $parcel, 2)<10)  
                                      $parcel--;
                                  ?> 
                              <small><?php echo $parcel;?>x de R$ <?php echo str_replace(".", ",", bcdiv($produto['price'], $parcel, 2));?></small></b>
                              </p>
                            </div>
                        </a>
                      </div>
                    </div>
                  <?php } ?>
              </div>
      </div>
      <?php }else{ ?>
      <h4 class="text-center">Sem produtos nesse departamento! </h4>
      <a href="http://192.168.1.4/app/view/home.php">
        <button data-role="none" class=" btn btn-primary btn-block">Voltar</button>
      </a>
      <?php }?>
    </section>
    <?php require_once('nav.php'); ?>
  </div>
</body>
</html>