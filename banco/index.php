<?php 
    require 'retriving.php';
  ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, height initial scale=1">

  <link rel="stylesheet" href="../app/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css" />
  <script src="../app/js/jquery.mobile-1.4.5/jquery-1.12.1.min.js"></script>
  <script src="../app/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>

  <script src="../app/js/app.js"></script>
  <script src="../app/js/bootstrap.min.js"></script>

  <script src="../app/js/slick-1.5.9/slick/slick.min.js"></script>  
  <link href="../app/js/slick-1.5.9/slick/slick.css" rel="stylesheet">

  <link href="../app/font-awesome-4.5.0/css/font-awesome.css" rel="stylesheet">
  <link href="../app/css/stylesheet.css" rel="stylesheet">
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
            <img src="http://127.0.1.1/app/images/banner/banner1.jpg" alt="...">
            <div class="carousel-caption">
                <h4>Woman's Fashion</h4>
            </div>
        </div>
        <div class="item">
            <img src="http://127.0.1.1/app/images/banner/banner2.jpg" alt="...">
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
      <a href="http://127.0.1.1/app/view/all_departaments.php?id=13" data-transition="slide" >
        <img class="img-responsive" src="http://127.0.1.1/app/images/categories/mens1.jpg" alt="..." style="opacity: 0.7">
        <!--div class="carousel-caption">
            <h4>Example headline.</h4>
        </div-->
      </a>
    </div>
    <div class="col-xs-4 col-sm-4" style="padding-right: 0px; padding-left: 0px ">
      <a href="http://127.0.1.1/app/view/all_departaments.php?id=3" data-transition="slide"  >
        <img class="img-responsive" src="http://127.0.1.1/app/images/categories/woman1.jpg" alt="..." style="opacity: 0.7">
      </a>
    </div>
    <div class="col-xs-4 col-sm-4" style="padding-right: 0px; padding-left: 0px">
      <a href="#">
        <img class="img-responsive" src="http://127.0.1.1/app/images/categories/couple1.jpg" style="opacity: 0.7">
      </a>
    </div>
  </div>
  <!-- END CATEGORIES -->

  <!--PRODUCTS -->
        <div class="row">
            <?php $i=0;  foreach ($img as $resource) {
            if($values[$i]!=0){
            $parcel=10; ?>
              <div class="col-xs-6 col-sm-3" style="padding-right: 2px; padding-left: 2px">
                <div class="thumbnail box-shadow--2dp"  >
                  <a class="nounderline"  href="<?php echo 'http://127.0.1.1/app/view/product.php?id='.$products[$i]['id'];?>" data-transition="slide">
                      <img  src="<?php echo 'http://127.0.1.1/prestashop'.$img[$i]?>" style="display: inline;">
                      <div class="caption" >
                        <small><?php echo substr_replace($name[$i], (strlen($name[$i]) > 17 ? '...' : ''), 17); ?></small>
                        <p><b>R$ <?php echo number_format((double)$values[$i], 2, ',', '.'); ;?><br>
                        <?php
                            while(bcdiv($values[$i], $parcel, 2)<10)  
                                $parcel--;
                            ?> 
                        <small><?php echo $parcel;?>x de R$ <?php echo str_replace(".", ",", bcdiv($values[$i], $parcel, 2));?></small></b>
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
      <?php include('../app/view/nav.php') ?>
  </div>
</body>
</html>       

