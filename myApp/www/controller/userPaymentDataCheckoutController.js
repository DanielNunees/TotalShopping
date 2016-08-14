app.controller('userPaymentDataCheckoutController',['$scope','$http','$ionicActionSheet', '$timeout','paymentCheckout','userDataFactory','$ionicNavBarDelegate','$window','$ionicPopup',function($scope,$http,$ionicActionSheet, $timeout,paymentCheckout,userDataFactory,$ionicNavBarDelegate,$window,$ionicPopup){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
    	$ionicNavBarDelegate.showBackButton(true);
    	$scope.loadUserData();
    	
  	});
  	var SenderHash;
  	$scope.method = 0;
  	$scope.user = {};
  	var checkoutData={};

	paymentCheckout.getSession().then(function successCallback(response) {
  		PagSeguroDirectPayment.setSessionId(response.data);
  		SenderHash = PagSeguroDirectPayment.getSenderHash();

        }, function errorCallback(response) {
        	/* Tratamento de erros*/
	      	//error 400 - No content
	      	if(response.status == 500){
	            var alertPopup = $ionicPopup.alert({
	            	title: 'Error 500',
	              	template: 'Serve Error! Checkout will be not proceed!',
	            });
          	}
	      	if(response.status==400){
	      		$scope.isEmpty = true;
	      	}
	      	else{$scope.isEmpty=false;}
	      	/* Fim Tratamento de erros*/
         	console.log(response);
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
	     	console.log(index);
	     	$scope.method = index;
	       return true;
	     }
	   });
	 };

	 $scope.loadUserData = function(){
		userDataFactory.loadUserData().then(function successCallback(response) {
      	$scope.isEmpty = false;
      	$scope.userData = response.data.address[0];
      	$scope.userBirth = response.data.user[0];
      	checkoutData.userData = response.data.address[0];
      	checkoutData.userBirth = response.data.user[0];
      	console.log(checkoutData.userData);
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

 	
	$scope.checkout = function(){
		var param = {
			cardNumber: $scope.user.cardnumber,
			cardBin:  $scope.user.cardnumber.slice(0,6),
			cvv: $scope.user.cvv,
			expirationMonth: $scope.user.expirationMonth,
			expirationYear: $scope.user.expirationYear,
			success: function(response) {
				console.log(response);
				console.log(PagSeguroDirectPayment.getSenderHash());
			//token gerado, esse deve ser usado na chamada da API do Checkout Transparente
			var cart = angular.fromJson($window.localStorage ['cart']);
			checkoutData.cart = JSON.parse(cart);
			checkoutData.cpf = '156.009.442-76';
			checkoutData.creditCardToken = response.card.token;
			checkoutData.SenderHash = PagSeguroDirectPayment.getSenderHash();
		paymentCheckout.creditCardCheckout(checkoutData).then(function successCallback(response) {
 			console.log(response.data);

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

			},
			error: function(response) {
			 //tratamento do erro
			 console.log(response);
			},
			complete: function(response) {
			//tratamento comum para todas chamadas
			}
		}
		//parâmetro opcional para qualquer chamada
		param.cardBin = $scope.user.cardnumber.slice(0,6);


		
		
		PagSeguroDirectPayment.createCardToken(param);
		




	}

	

	



}])