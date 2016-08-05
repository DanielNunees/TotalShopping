app.controller('userPaymentDataCheckoutController',['$scope','$http','$ionicActionSheet', '$timeout','paymentCheckout','userDataFactory','$ionicNavBarDelegate',function($scope,$http,$ionicActionSheet, $timeout,paymentCheckout,userDataFactory,$ionicNavBarDelegate){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
    	$ionicNavBarDelegate.showBackButton(true);
    	$scope.loadData();
  	});

	paymentCheckout.getSession().then(function successCallback(response) {
  		PagSeguroDirectPayment.setSessionId(response.data);
  		PagSeguroDirectPayment.getSenderHash();
  		PagSeguroDirectPayment.getPaymentMethods({
			amount: 500.00,
			success: function(response) {
				console.log(response);
				console.log(response.paymentMethods.CREDIT_CARD.options.VISA.images.MEDIUM.path);
				$scope.pagseguro =response.paymentMethods.CREDIT_CARD.options.VISA.images.MEDIUM.path;
				//meios de pagamento disponíveis
			},
			error: function(response) {
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

	$scope.show = function() {

	   // Show the action sheet
	   var hideSheet = $ionicActionSheet.show({
	     buttons: [
	       { text: 'Cartão de Crédito' },
	       { text: 'Boleto Bancário' },
	       { text: 'Saldo do PagSeguro' },
	       { text: 'Débito Online' }
	     ],
	     titleText: 'Métodos de Pagamento',
	     cancelText: 'Cancel',
	     cancel: function() {
	          // add cancel code..
	        },
	     buttonClicked: function(index) {
	     	$scope.method = index;
	       return true;
	     }
	   });
	 };
	 $scope.loadData = function(){
		userDataFactory.loadUserData().then(function successCallback(response) {
      	$scope.isEmpty = false;
      	$scope.userData = response.data.address[0];
      	$scope.userBirth = response.data.user[0];
      	angular.forEach($scope.states2,function(value,key){
      		if(response.data.address[0].id_state==value.value){
      			$scope.userData.state = value.state;
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

	



}])