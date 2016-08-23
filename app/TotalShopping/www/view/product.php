<?php session_start();?>
<?php require('../controller/combinations.php');
function __autoload($class_name) {
    require_once $cart . '.php';
}
 ?>
<!DOCTYPE html>
<html>
<head>
  <?php  require('head.php'); ?>
</head>
<body>
  <div data-role="page" id="product" >
  <section >
  <?php $parcel=10;?>
  <div class="thumbnail container "  >
    <?php if(isset($products[0]['image'])){ ?>
      <img class="center-block" id="pic" src="<?php echo SERVER.'/prestashop'.$products[0]['image'];?>" width="300" height="300" >
      <?php }else{?>
          <img class="center-block" src="<?php echo SERVER.'/prestashop/img/p/br-default-thickbox_default.jpg'?>" width="300" height="300">
      <?php } ?>
      <div class="caption" id="extra_pics" >
        <?php if(isset($final)) { ?>
          <?php $q=0; foreach ($final[$q]['photos'] as $img) { ?>
            <img onclick="zoom_min_pics('<?php echo $final[0][0] ?>',<?php echo $q ?>)" class="img-thumbnail left-block" id="min_pics" src="<?php echo SERVER.'/prestashop'.$img?>" width="93" height="93">
         <?php $q++; }?>
        <?php } ?> 
      </div>
  </div>
    <div style="background-color:white;" class="container" >
        <h3 ><?php echo ($products[0]['name']); ?></h3>
        <p style="margin:0px">
          <b><h2 style="margin:0px">R$ <?php echo number_format((double)$products[0]['price'], 2, ',', '.'); ;?></h2>
          <?php  
            while(bcdiv($products[0]['price'], $parcel, 2)<10)  
              $parcel--;
          ?> 
          <?php echo $parcel;?>x de R$ <?php echo str_replace(".", ",", bcdiv($products[0]['price'], $parcel, 2));?></b>
        </p>

      <div class="row ">
        <div class="col-xs-4" >
          <h3 style="margin:0px">Quantidade:</h3>
        </div>
        <div class="col-xs-8" >
          <div class="pull-right btn-group">
            <input type="button" data-role="none" onclick="sub()" type="button" class="btn btn-primary" value="-"></input>
            <p id="demo" class="btn disabled ">1</p>
            <input type="button" data-role="none" onclick="add()" type="button" class="btn btn-primary" value="+"></input>
          </div>
        </div>
      </div>
      <br>

      <!--Tamanho!-->
      <?php if(!$two_options){  ?>
        <div class="row ">
          <div class="col-xs-4" >
            <h3 style="margin:0px">
            <?php if(strcmp($final[0][0],"Tamanho Único")==0){ ?>
            Tamanho:
            <?php }elseif(is_string($final[0][0])){ ?>
            Cor:
            <?php }else{ ?>
            Tamanho:
            <?php } ?>
            </h3> <!-- compara o $final[0][0] com string ou int if int->tamanho ou cor -->
          </div>
          <div class="col-xs-8" >
            <select id="selector_one_opt" data-role="none" name="cars"class="pull-right btn btn-transparent btn-default dropdown-toggle">
              <?php foreach ($final as $key => $value) {?>
                <option  value="<?php echo $value[0];?>"><?php echo $value[0];?> </option>
              <?php  } ?>
            </select>              
          </div>
        </div>
        <?php } ?>
        
        <!--Opcao: painel de teste>
        <div class="panel panel-default">
          <div class="panel-body" style="background-color:#000"></div>
        </div-->

        <?php if($two_options){  ?>
        <!-- Tamanhos -->
        <div class="row ">
          <div class="col-xs-4" >
            <h3 style="margin:0px">
            Tamanho:
            </h3> <!-- comparar o $final[1] com string ou int if int->tamanho ou cor -->
          </div>
          <div class="col-xs-8" >
            <select id="sizes" data-role="none" name="cars"class="pull-right btn btn-transparent btn-default dropdown-toggle">
              <?php for($i=1;$i<count($final[0]);$i+=2) { ?>
                <?php if(isset($final[0][$i])) { ?>
                  <option id="size_option_name" value="<?php echo $final[0][$i];?>"><?php echo $final[0][$i];?> </option>
                <?php  } ?>
              <?php }?>
            </select>
          </div>
        </div>

        <!-- CORES --> 
        <div class="row ">
          <div class="col-xs-4" >
            <h3 style="margin:0px">Cor:</h3> <!-- comparar o $final[1] com string ou int if int->tamanho ou cor -->
          </div>
          <div class="col-xs-8" >
          <select id="selector_one_opt" data-role="none" name="cars"class="pull-right btn btn-transparent btn-default dropdown-toggle">
              <?php foreach ($final as $value) {?>
                  <option  value="<?php echo $value[0];?>"><?php echo $value[0];?> </option>
              <?php }?>
            </select>
          </div>
        </div>
      <?php } ?>

      <br>
      <input type="button" onclick="adicionar_ao_carrinho();" data-role="none" class="btn btn-primary btn-block" value="Adicionar ao carrinho"></input>
        <p><?php echo $products[0]['description']; ?></p>
    </div>
<?php $_SESSION["favcolor"] = "green";
$_SESSION["favanimal"] = "cat";?>
</section>
      <?php require('nav.php') ?>
      <script>
    var i = 1;
    function add() {
        if(i<5){
          document.getElementById('demo').innerHTML = ++i;
        }
    }
    function sub() {
        if(i>1) 
          document.getElementById('demo').innerHTML = --i;
    }

    $('#selector_one_opt').on('change', function() {
      var id = $(this).val();
      <?php $i=0; foreach($final as $end){ ?>
          if (id.localeCompare("<?php echo $end[0];?>")==0) {
            <?php if(isset($end['photos'][0])){ ?>
              document.getElementById("pic").innerHTML = '';
              document.getElementById("pic").src = "<?php echo SERVER.'/prestashop'.$end['photos'][0]; ?>";
            <?php }?>
              
              document.getElementById("extra_pics").innerHTML = '';
              <?php $img=0; foreach ($end['photos'] as $image) { ?>
                var elem = document.createElement("img");
                elem.setAttribute("onclick", "zoom_min_pics('<?php echo $end[0]; ?>',<?php echo $img; ?>)");
                elem.setAttribute("class", "img-thumbnail left-block");
                elem.setAttribute("id", "min_pics");
                elem.setAttribute("src", "<?php echo SERVER.'/prestashop'.$image?>");
                elem.setAttribute("width", "93");
                elem.setAttribute("height", "93");
                document.getElementById("extra_pics").appendChild(elem);
                <?php $img++;} ?>
              <?php if($two_options) { ?> 
                

                document.getElementById("sizes").innerHTML = '';
                <?php for($j=1;$j<count($final[$i]);$j+=2){ ?>
                  <?php if(isset($final[$i][$j])) { ?>
                    $("#sizes").append('<option value="<?php  echo $end[$j]; ?>"><?php  echo $end[$j]; ?></option>');   
                  <?php } ?>
                <?php } ?>
              <?php } ?>
          }
    <?php $i++; } ?>
    });

    

  function size_combination(id){
    <?php  foreach($final as $end){$i=1; ?>
      <?php while(isset($end[$i])) { ?>
          if (id.localeCompare("<?php echo $end[$i];?>")==0) {
            document.getElementById("size_option_name").innerHTML = "<?php  echo $end[$i]; ?>";
          }
          <?php $i+=2;}?>
          <?php  } ?>



  }

    function zoom_min_pics(id,img){
      <?php  foreach($final as $end){$i=0; ?>
          if (id.localeCompare("<?php echo $end[0];?>")==0) {
                <?php foreach($end['photos'] as $imgs) { ?>
                  if(img==<?php echo $i; ?>){
                    document.getElementById("pic").src = "<?php echo SERVER.'/prestashop'.$imgs; ?>";
                  }
                <?php $i++;}  ?>
            }
      <?php } ?>
    }



</script>

</div>


</body>
</html>