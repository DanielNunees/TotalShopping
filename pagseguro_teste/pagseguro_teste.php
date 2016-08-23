<?php require_once "PagSeguroLibrary/PagSeguroLibrary.php" ?>
<!DOCTYPE html>
<html>
<head>
	<title>Pagseguro</title>
</head>
<body>
<?php 
$paymentRequest = new PagSeguroPaymentRequest();
    	$paymentRequest->addItem('0001', 'Notebook', 1, 2430.00);
    	$paymentRequest->addItem('0002', 'Mochila',  1, 150.99);
    	$sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');
    	$paymentRequest->setShippingType($sedexCode);
    	$paymentRequest->setShippingAddress(  
		  '01452002',  
		  'Av. Brig. Faria Lima',  
		  '1384',  
		  'apto. 114',  
		  'Jardim Paulistano',  
		  'São Paulo',  
		  'SP',  
		  'BRA'  
		);
		$paymentRequest->setSender(  
		  'João Comprador',  
		  'email@comprador.com.br',  
		  '11',  
		  '56273440',  
		  'CPF',  
		  '156.009.442-76'  
		);
		$paymentRequest->setCurrency("BRL");
		$paymentRequest->setReference("REF123");
		$paymentRequest->setRedirectUrl("http://localhost/laravel/public/");
		$paymentRequest->addParameter('notificationURL', 'http://www.lojamodelo.com.br/nas');
		$paymentRequest->addPaymentMethodConfig('CREDIT_CARD', 1.00, 'DISCOUNT_PERCENT');  
		$paymentRequest->addPaymentMethodConfig('EFT', 2.90, 'DISCOUNT_PERCENT');  
		$paymentRequest->addPaymentMethodConfig('BOLETO', 10.00, 'DISCOUNT_PERCENT');  
		$paymentRequest->addPaymentMethodConfig('DEPOSIT', 3.45, 'DISCOUNT_PERCENT');  
		$paymentRequest->addPaymentMethodConfig('BALANCE', 0.01, 'DISCOUNT_PERCENT');


    try {  
      
      $credentials = PagSeguroConfig::getAccountCredentials(); // getApplicationCredentials() 
      //var_dump($credentials);
      $checkoutUrl = $paymentRequest->register($credentials);  
      
    } catch (PagSeguroServiceException $e) {  
        die($e->getMessage());  
    }
//var_dump($paymentRequest); ?>
</body>
</html>