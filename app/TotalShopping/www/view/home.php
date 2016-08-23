<?php  require_once('../controller/product_home.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <?php  require_once('head.php'); ?> 
</head>
<body>
  <div id="home" data-role="page">
    <section>
     <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <!--li data-target="#carousel-example-generic" data-slide-to="2"></li-->
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="<?php echo SERVER.'/app/images/banner/banner1.jpg'?>" alt="...">
            <div class="carousel-caption">
                <h4>Woman's Fashion</h4>
            </div>
        </div>
        <div class="item">
            <img src="<?php echo SERVER.'/app/images/banner/banner2.jpg'?>" alt="...">
            <div class="carousel-caption">
                <h4>Mens Fashion</h4>
            </div>  
        </div>
    </div>                  
</div>
<!--END BANNERS-->
<!-- CATEGORIES -->
<div  class="container home-color">
  <div class="row">
    <div class="col-xs-4 col-sm-4" style="padding-right: 0px; padding-left: 0px">
      <a href="<?php echo SERVER.'/app/TotalShopping/www/view/all_departaments.php?id=13' ?>" data-transition="slide" >
        <img class="img-responsive" src="<?php echo SERVER.'/app/images/categories/mens1.jpg'?>" alt="..." style="opacity: 0.7">
        <!--div class="carousel-caption">
            <h4>Example headline.</h4>
        </div-->
      </a>
    </div>
    <div class="col-xs-4 col-sm-4" style="padding-right: 0px; padding-left: 0px ">
      <a href="<?php echo SERVER.'/app/TotalShopping/www/view/all_departaments.php?id=3' ?>" data-transition="slide"  >
        <img class="img-responsive" src="<?php echo SERVER.'/app/images/categories/woman1.jpg'?>" alt="..." style="opacity: 0.7">
      </a>
    </div>
    <div class="col-xs-4 col-sm-4" style="padding-right: 0px; padding-left: 0px">
      <a href="#">
        <img class="img-responsive" src="<?php echo SERVER.'/app/images/categories/couple1.jpg'?>" style="opacity: 0.7">
      </a>
    </div>
  </div>
  <!-- END CATEGORIES -->
    

  <!--PRODUCTS -->
        <?php shuffle($products);?>
        <div class="row">
            <?php $i=0;  foreach ($resources as $resource) {
            if($products[$i]['price']!=0){
            $parcel=10; ?>
              <div class="col-xs-6 col-sm-3" style="padding-right: 2px; padding-left: 2px">
                <div class="thumbnail box-shadow--2dp"  >
                  <a class="nounderline"  href="<?php echo SERVER.'/app/TotalShopping/www/view/product.php?id='.$products[$i]['id']?>" data-transition="slide">
                    <?php if(isset($products[$i]['image'])){ ?>
                      <img  src="<?php echo SERVER.'/prestashop'.$products[$i]['image']?>" style="display: inline;">
                    <?php } else{?>
                      <img src="<?php echo SERVER.'/prestashop/img/p/br-default-thickbox_default.jpg'?>">
                    <?php } ?>
                      <div class="caption" >
                        <small><?php echo substr_replace($products[$i]['name'], (strlen($products[$i]['name']) > 17 ? '...' : ''), 17); ?></small>
                        <p><b>R$ <?php echo number_format((double)$products[$i]['price'], 2, ',', '.'); ;?><br>
                        <?php
                            while(bcdiv($products[$i]['price'], $parcel, 2)<10)  
                                $parcel--;
                            ?> 
                        <small><?php echo $parcel;?>x de R$ <?php echo str_replace(".", ",", bcdiv($products[$i]['price'], $parcel, 2));?></small></b>
                        </p>
                      </div>
                  </a>
                </div>
              </div>
             <?php ?> 
             <?php } ?>
            <?php $i++;} ?>
        </div>
  <!--END PRODUCTS-->

    </section>
      <?php include('nav.php') ?>
  </div>
</body>
</html>       

