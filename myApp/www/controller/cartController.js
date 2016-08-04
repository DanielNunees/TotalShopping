app.controller('cartController', ['$scope','$ionicNavBarDelegate','$http','$httpParamSerializerJQLike',  function($scope,$ionicNavBarDelegate,$http,$httpParamSerializerJQLike){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
    });
 	
 	$scope.checkout = function(){
		$http({
        method: 'POST',
        url: 'http://127.0.1.1/laravel/public/getSession',
        dataType: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: $httpParamSerializerJQLike({})
        
      }).then(function successCallback(response) {
  		PagSeguroDirectPayment.setSessionId(response.data);
  		PagSeguroDirectPayment.getSenderHash();
  		PagSeguroDirectPayment.getPaymentMethods({
			amount: 500.00,
			success: function(response) {
				console.log(response.paymentMethods.CREDIT_CARD.options.VISA.images.MEDIUM.path);
				$scope.pagseguro =response.paymentMethods.CREDIT_CARD.options.VISA.images.MEDIUM.path;
				//meios de pagamento disponíveis
			},
			error: function(response) {
				console.log('ok');
				console.log(response);
				//tratamento do erro
			},
			complete: function(response) {
				console.log('complete');
				//tratamento comum para todas chamadas
			}
		});

        }, function errorCallback(response) {
        	/* Tratamento de erros*/
	      	//error 400 - No content
	      	if(response.status==400){
	      		$scope.isEmpty = true;
	      	}
	      	else{$scope.isEmpty=false;}
	      	/* Fim Tratamento de erros*/
         	console.log(response.data);
        });
	}
	

  		


}]);