<?php session_start(); ?>
<?php  require_once('../controller/cart.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<?php require_once('head.php'); ?>
</head>
<body>
	<div id="cart" data-role="page" >
	    <section>
		    <div class="container">
			  <h2>Carrinho de compras</h2>            
			  <table class="table">
			    <thead>
			      <tr>

			        <th>Firstname</th>
			        <th>Lastname</th>
			        <th>Email</th>
			      </tr>
			    </thead>
			    <tbody>
			      <tr class="success">
			        <td>John</td>
			        <td>Doe</td>
			        <td>john@example.com</td>
			      </tr>
			      <tr class="danger">
			        <td>Mary</td>
			        <td>Moe</td>
			        <td>mary@example.com</td>
			      </tr>
			      <tr class="info">
			        <td>July</td>
			        <td>Dooley</td>
			        <td>july@example.com</td>
			      </tr>
			    </tbody>
			  </table>
			</div>
	    </section>
	    <?php require_once('nav.php'); ?>
	</div>

</body>
</html>