
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

    function pictures_combinations(id){
      <?php if(isset($val2)){ ?>
        <?php $q=0; foreach ($val2 as $vl2){ ?>
          if (id==<?php echo $vl2;?>) {
            <?php if(isset($imagens[$vl2][0])){ ?>
              document.getElementById("pic").src = "<?php echo 'http://127.0.1.1/prestashop'.$imagens[$vl2][0]; ?>";
            <?php } ?>
            <?php if(isset($valores2[$q])){ ?>
              document.getElementById("color_option_name").innerHTML = "<?php  echo $valores2[$q]; ?>";
            <?php }?>
            <?php if(isset($imagens[$vl2])){ ?>
                  document.getElementById("extra_pics").innerHTML = '';
                  <?php $id=0; foreach ($imagens[$vl2] as $image) { ?>
                    var elem = document.createElement("img");
                    elem.setAttribute("onclick", "zoom_min_pics(<?php echo $vl2; ?>,<?php echo $id; ?>)")
                    elem.setAttribute("class", "img-thumbnail left-block");
                    elem.setAttribute("id", "min_pics");
                    elem.setAttribute("src", "<?php echo 'http://127.0.1.1/prestashop'.$image?>");
                    elem.setAttribute("width", "100");
                    elem.setAttribute("height", "100");
                    document.getElementById("extra_pics").appendChild(elem);
                  <?php $id++;} ?>
            <?php } ?>
            document.getElementById("size_option_name").innerHTML = "<?php  echo $product_options_values[$sizes[$vl2][0]]; ?>";
            document.getElementById("sizes").innerHTML = '';
            <?php foreach($sizes[$vl2] as $size){ ?>
              var elem = document.createElement("a");
              var textnode = document.createTextNode("<?php echo $product_options_values[$size];?>");
              elem.setAttribute("onclick", "size_combination(<?php echo $size ?>)");
              elem.appendChild(textnode);
              document.getElementById("sizes").appendChild(elem);   
            <?php } ?>
          }
        <?php $q++; } ?>
      <?php } ?>
    }

    function size_combination(id){
      <?php if(isset($val1) && !isset($val2)){ ?> 
          <?php  foreach ($val1 as $vl1){ ?>
                if (id==<?php echo $vl1;?>){
                  document.getElementById("size_option_name").innerHTML = "<?php  echo $product_options_values[$vl1]; ?>";
                  <?php if(isset($imagens1[$vl1][0])){ ?>
                    document.getElementById("pic").src = "<?php echo 'http://127.0.1.1/prestashop'.$imagens1[$vl1][0]; ?>";
                  <?php } ?>
                  <?php if(isset($imagens1[$vl1])){ ?>
                  document.getElementById("extra_pics").innerHTML = '';
                  <?php $id=0; foreach ($imagens1[$vl1] as $image) { ?>
                    var elem = document.createElement("img");
                    elem.setAttribute("onclick", "zoom_min_pics(<?php echo $vl1; ?>,<?php echo $id; ?>)")
                    elem.setAttribute("class", "img-thumbnail left-block");
                    elem.setAttribute("id", "min_pics");
                    elem.setAttribute("src", "<?php echo 'http://127.0.1.1/prestashop'.$image?>");
                    elem.setAttribute("width", "100");
                    elem.setAttribute("height", "100");
                    document.getElementById("extra_pics").appendChild(elem);
                  <?php $id++;} ?>
            <?php } ?>

                }
          <?php  } ?>
      <?php  } ?>
      <?php if(isset($val2)){ ?> 
          <?php  foreach ($val2 as $vl2){ ?>
            <?php foreach ($sizes[$vl2] as $size) { ?>
                if (id==<?php echo $size;?>){
                  document.getElementById("size_option_name").innerHTML = "<?php  echo $product_options_values[$size]; ?>";
                }
            <?php } ?>
          <?php  } ?>
      <?php  } ?>
    }

    function zoom_min_pics(id,img){
      <?php if(isset($val1) && !isset($val2)){ ?> 
        <?php $q=0; foreach ($val1 as $vl1){ ?>
          <?php if(isset($imagens1[$vl1])){?>
            if(id == <?php echo $vl1;?>){
                <?php foreach ($imagens1[$vl1] as $imgs) { ?>
                  if(img==<?php echo $q; ?>){
                    document.getElementById("pic").src = "<?php echo 'http://127.0.1.1/prestashop'.$imgs; ?>";
                  }
                <?php $q++;}  ?>
            }
            <?php } ?>
        <?php  $q=0;} ?>
      <?php }if(isset($val2)){ ?>  
        <?php $q=0; foreach ($val2 as $vl2){ ?>
          <?php if(isset($imagens[$vl2])){?>
            if(id == <?php echo $vl2;?>){
                <?php foreach ($imagens[$vl2] as $imgs) { ?>
                  if(img==<?php echo $q; ?>){
                    document.getElementById("pic").src = "<?php echo 'http://127.0.1.1/prestashop'.$imgs; ?>";
                  }
                <?php $q++;} ?>
            }
            <?php } ?>
        <?php  $q=0;} ?>
      <?php } ?>
    }
