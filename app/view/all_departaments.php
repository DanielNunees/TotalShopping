<?php require_once('../controller/all_departaments.php');?>
<!DOCTYPE html>
<html>
<head>
  <?php require_once('head.php'); ?>
</head>

<body>
  <div id="all_departaments" data-role="page" >
    <section>
      <?php if(isset($_GET["id"])){$id = $_GET["id"];}else{ ?>
        <h3>All departaments ID error!</h3>
      <?php }?>
      <?php  if(isset($products)) {  ?>
        <?php $cate=0; $i=0; foreach($products as $product){ ?>
        <div  class=" container home-color"  >
          <a data-transition="slide" class="nounderline " href="<?php echo 'http://127.0.1.1/app/view/departament.php?id='.$id.'&categoria='.$categories_id[$cate]?>">
              <b><h3 style="display: inline; color:black;">
              <?php echo $product['name']; ?></h3></b><h4 style=" margin: 0 ">Mais</h4>
          </a>
          <div data-enhance="false" class="row">
              <div data-enhance="false" class="multiple-items" >
                  <?php $i=0; while(isset($product['produtos'][$i])){ ?>
                      <?php $parcel=10; ?>
                      <div class="col-xs-4 col-sm-4" style="padding-right: 2px; padding-left: 2px">
                              <div class="thumbnail box-shadow--2dp"   >
                                  <a class="nounderline" href="<?php echo 'http://127.0.1.1/app/view/product.php?id='.$product['produtos'][$i]['id'];?>" data-transition="slide" >
                                      <?php if(isset($product['produtos'][$i]['image'])){ ?>
                                          <img src="<?php echo 'http://127.0.1.1/prestashop'.$product['produtos'][$i]['image'];?>">
                                      <?php } else{?>
                                          <img src="http://127.0.1.1/prestashop/img/p/br-default-thickbox_default.jpg">
                                      <?php } ?>
                                      <div class="caption"   >
                                          <small><?php echo substr_replace($product['produtos'][$i]['name'], (strlen($product['produtos'][$i]['name']) > 11 ? '...' : ''), 11);?></small>
                                          <p><b>R$ <?php echo number_format((double)$product['produtos'][$i]['price'][0], 2, ',', '.'); ;?><br>
                                            <?php while(bcdiv($product['produtos'][$i]['price'][0], $parcel, 2)<10) $parcel--;?>
                                              <small><?php echo $parcel;?>x R$ <?php echo str_replace(".", ",", bcdiv($product['produtos'][$i]['price'][0], $parcel, 2));?></small></b>
                                          </p>
                                      </div>
                                  </a>
                              </div>
                      </div>
                  <?php $i++;}?>
              </div>   
          </div>      
    </div>
<?php  $cate++;}  ?>
      <?php }else{ ?>
      <h4>Sem produtos nesse departamento! </h4>
      <a href="http://127.0.1.1/app/view/home.php">
        <button class="btn-primary">Voltar</button>
      </a>
      <?php }?>
    </section>
    <?php require_once('nav.php'); ?>
  </div>
</body>
</html>