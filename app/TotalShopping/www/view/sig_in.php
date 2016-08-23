<?php require('../controller/states.php');?>
<!DOCTYPE html>
<html>
<head>
	<?php require('head.php'); ?>
</head>
<body>
	<div id="all_departaments" data-role="page" >
	
		<section>
		<form class="form-horizonta container" method="post" id="sigin_form" >
				<fieldset>
				<input type="hidden" name="newsletter" value="1" >
	  			<input type="hidden" name="optin" value="1"> 
	  		 	<input type="hidden" name="active" value="1"> 

				<!-- Form Name -->
				<legend>Dados Pessoais:</legend>

				<!-- Text input-->
				<div  class="form-group">
				  <label class="col-md-4 control-label" for="Nome:">Nome:</label>  
				  <div  class="col-md-5">
				  <input id="firstname" name="firstname" type="text" placeholder="ex.  Daniel" class="form-control input-md" required="">
				    
				  </div>
				</div>				 

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="sobrenome">Sobrenome:</label>  
				  <div class="col-md-4">
				  <input id="lastname" name="lastname" type="text" placeholder="ex. Silva" class="form-control input-md" required="">
				    
				  </div>
				</div>

				<!-- Select Basic -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="selectbasic">Sexo:</label>
				  <div class="col-md-4">
				    <select data-role="none" id="id_gender" name="id_gender" class="form-control" >
				      <option value="1">Masculino</option>
				      <option value="2">Feminino</option>
				    </select>
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="date">Aniversário:</label>  
				  <div class="col-md-4">
				  <input data-role="none"id="birthday" name="birthday" type="date" class="form-control input-md" required="">
				    
				  </div>
				</div>


				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="email">Telefone:</label>  
				  <div class="col-md-4">
				  <input maxlength="9"  minlength="8" id="phone_mobile" name="phone_mobile" type="text" placeholder="ex .(xx) xxxx-xxxx" class="form-control input-md" required="">
				    
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="email">Email:</label>  
				  <div class="col-md-4">
				  <input id="email" name="email" type="email" placeholder="ex. exemplo@exemplo.com" class="form-control input-md" required="">
				    
				  </div>
				</div>

				<!-- Password input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="password">Senha</label>
				  <div class="col-md-4">
				    <input minlength="5" id="passwd" name="passwd" type="password" placeholder="" class="form-control input-md" required="">
				    <span class="help-block">(mín. 5 caracteres)</span>
				  </div>
				</div>

				<legend>Endereço:</legend>

				<!-- Select Basic -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="id_state">Estado:</label>
				  <div class="col-md-4">
				    <select data-role="none" id="id_state" name="id_state" class="form-control">
				    <?php foreach ($states as $key => $state){ ?>
				      <option value="<?php echo $key ?>"><?php echo $state ?></option>
				    <?php } ?>  
				    </select>
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="city">Cidade:</label>  
				  <div class="col-md-4">
				  <input id="city" name="city" type="text" placeholder="" class="form-control input-md" required="">
				    
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="address1">Endereço:</label>  
				  <div class="col-md-4">
				  <input id="address1" name="address1" type="text" placeholder="Rua, avenida..." class="form-control input-md" required="">
				    
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="address2">Bairro:</label>  
				  <div class="col-md-4">
				  <input id="address2" name="address2" type="text" placeholder="" class="form-control input-md" required="">
				    
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="postcode">CEP:</label>  
				  <div class="col-md-4">
				  <input maxlength="8"  minlength="8" id="postcode" name="postcode" type="text" placeholder="" class="form-control input-md" required="">
				    
				  </div>
				</div>




				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="submit"></label>
				  <div class="col-md-4">
				    <input data-role="none" id="send" name="submit" type="submit" class="btn btn-primary btn-block"></input>
				  </div>
				</div>


				</fieldset>
				</form>

		</section>
		<?php require('nav.php'); ?>
	</div>

	<script >
			$('#sigin_form').submit(function(e){
				e.preventDefault();

				if($('#send').val() == 'Enviando...'){
					return(false);
				}

				$('#send').val('Enviando...');

				$.ajax({
					url:'../controller/sig_in.php',
					type:'post',
					dataType:'html',
					data: {
						'id_gender': $('#id_gender').val(),
						'birthday': $('#birthday').val(),
						'firstname': $('#firstname').val(),
						'lastname': $('#lastname').val(),
						'id_state': $('#id_state').val(),
						'email': $('#email').val(),
						'phone_mobile': $('#phone_mobile').val(),
						'passwd': $('#passwd').val(),
						'address1': $('#address1').val(),
						'address2': $('#address2').val(),
						'city': $('#city').val(),
						'postcode': $('#postcode').val(),
						'active': '1',
						'optin': '1'
						}
					}).done(function(data){
						alert(data);
						$('#send').val('Enviar');
						$('#id_gender').val('Masculino');
						$('#birthday').val('');
						$('#firstname').val('');
						$('#lastname').val('');
						$('#email').val('');
						$('#phone_mobile').val('');
						$('#passwd').val('');
						$('#address1').val('');
						$('#address2').val('');
						$('#city').val('');
						$('#postcode').val('');	

			});
		});


	</script>

</body>
</html>