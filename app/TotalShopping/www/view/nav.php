<?php define("SERVER1", "http://".$_SERVER['SERVER_NAME']); ?>

<!--div data-position="fixed">
<nav data-role="none" class="nav navbar-default nav-justified" >
  <div  class="row">
  <div class="col-xs-3 col-sm-3" style="margin-top: 4px">
          <a href="http://127.0.1.1/app/view/home.php" data-transition="slide" data-direction="reverse">
            <button data-role="none" type="button" class="btn btn-transparent">
              <i class="fa fa-th baricons fa-lg"></i>
                </button></a>
    </div>
    <div class="col-xs-3 col-sm-3">
          <a href="#">
            <button data-role="none" type="button" class="btn btn-transparent">
              <i class="fa fa-shopping-bag baricons fa-lg "></i><span class="badge">0</span>
                </button></a>
    </div>
    <div class="col-xs-3 col-sm-3">
          <a href="#">
            <button data-role="none" type="button" class="btn btn-transparent">
              <i class="fa fa-search baricons fa-lg "></i>
                </button></a>
    </div>

    <div class="col-xs-3 col-sm-3" style="margin-top: 2px">
      <div class="dropup">
            <button data-role="none" type="button" class="btn btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">         
          <i class="fa fa-user baricons fa-lg"></i><span class="caret baricons"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
              <li><a href="#">Login</a></li>
              <li><a href="#">Cadastre-se</a></li>
            </ul>
        </div>
    </div>
    
  </div>
</nav>
</div-->


<div data-role="footer"  data-position="fixed" data-tap-toggle="false" data-id="f1"  data-theme="a" >
  <div data-role="navbar">
    <ul>
      <li><a href="<?php echo SERVER1.'/app/TotalShopping/www/view/home.php'?>" class="ui-btn-active ui-state-persist" data-transition="slide" data-icon="grid"  data-direction="reverse"></a></li>
      <li><a href="<?php echo SERVER1.'/app/TotalShopping/www/view/cart.php'?>" data-transition="slide"  data-direction="reverse"  data-icon="shop" ></a></li>
      <li><a href="#" data-transition="slide" data-direction="reverse"  data-icon="search" ></a></li>
      <li><a href="<?php echo SERVER1.'/app/TotalShopping/www/view/sig_in.php'?>" data-transition="slide"  data-icon="user"></a></li>
    </ul>
  </div>
</div>