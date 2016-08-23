<?php require_once('../controller/all_departaments.php');?>
<!DOCTYPE html>
<html>
<head>
<?php require_once('head.php'); ?>
</head>
<body>
  <div data-role="page">
      <?php if(isset($_GET["id"])){$id = $_GET["id"];}else{ ?>
        <h3>All departaments ID error!</h3>
      <?php }?>
      <?php  if(isset($products)) {  ?>
        <div>
            <ul class="nav nav-tabs nav-justified">
              <?php $cate=0; foreach($products as $product){ ?>
                <?php if($cate ==0){ ?>
                  <li class="active">
                <?php }else{  ?> 
                  <li >
                <?php } ?>
                  <a data-toggle="tab"  href="#<?php echo $cate?>" >
                    <?php echo $product['name']; ?>
                  </a>
                </li>       
              <?php $cate++;}  ?>
           </ul>
        </div>
      <?php }else{ ?>
        <h4>Sem produtos nesse departamento! </h4>
        <a data-transition="slide" href="http://127.0.1.1/app/view/home.php">
          <button class="btn-primary">Voltar</button>
        </a>
      <?php }?>
    </header>
    
    <section>
      <div class="tab-content">
      <?php $cate=0; foreach($products as $product){ ?>
      <?php if($cate ==0){ ?>
        <div id="<?php echo $cate; ?>" class="tab-pane fade in active">
      <?php }else{  ?> 
        <div id="<?php echo $cate; ?>" class="tab-pane fade">
      <?php } ?>
      <div class="container "  >

            <div class="row ">
                  <?php $i=0;  foreach($produtos as $produto) {
                  $parcel=10; ?>
                  <?php if(isset($product['products_ids'][$i])){ ?>
                  <?php if($product['products_ids'][$i]==$produto['id']){?>
                    <div class="col-xs-6 col-sm-4" style="padding-right: 2px; padding-left: 2px">
                      <div class="thumbnail box-shadow--2dp"  >
                        <a class="nounderline" href="<?php echo 'http://127.0.1.1/app/view/product.php?id='.$produto['id'];?>" data-transition="slide" >
                          <?php if(isset($produto['image'])){ ?>
                            <img src="<?php echo 'http://127.0.1.1/prestashop'.$produto['image'];?>">
                          <?php } else{?>
                            <img src="http://127.0.1.1/prestashop/img/p/br-default-thickbox_default.jpg">
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

                   <?php $i++;}else $i=0;?> 
                  <?php } ?>
                  <?php } ?>
              </div>        
      </div>
      </div>
      <?php $cate++;}  ?>
        
        </div>
    </section>





    <?php require_once('nav.php'); ?>
  </div>
</body>
</html>