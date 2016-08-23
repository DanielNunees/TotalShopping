<!DOCTYPE html>
<html lang="pt-BR" >
<head>
<meta charsert="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Document</title>

  <link href="font-awesome-4.5.0/css/font-awesome.css" rel="stylesheet">
  <link href="css/stylesheet.css" rel="stylesheet">

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>


<script type="text/javascript" src="app.js">
$( document ).ready(function(){
    
    $.ajax({
        type:"get",
        url:"http://127.0.1.1/app/carousel_test.php",
        dataType:"html"
    }).done(function(data){
        $('#header').html(data);
    }); 
});</script>
</head>
<body>
	<div data-role="page" id="header">
		

	




	</div>
</body>
</html>