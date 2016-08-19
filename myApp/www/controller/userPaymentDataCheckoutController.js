app.controller('userPaymentDataCheckoutController',['$scope','$http','$ionicActionSheet', '$timeout','paymentCheckout','userDataFactory','$ionicNavBarDelegate','$window','$ionicPopup','$ionicLoading',function($scope,$http,$ionicActionSheet, $timeout,paymentCheckout,userDataFactory,$ionicNavBarDelegate,$window,$ionicPopup,$ionicLoading){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
    	$ionicNavBarDelegate.showBackButton(true);
    	$scope.loadUserData();
    	$scope.showLoading();
    	paymentCheckout.getSession().then(function successCallback(response) {

  		PagSeguroDirectPayment.setSessionId(response.data);
  		SenderHash = PagSeguroDirectPayment.getSenderHash();
  		$scope.hideLoading();
        }, function errorCallback(response) {
        	/* Tratamento de erros*/
	      	//error 400 - No content
	      	if(response.status == 500){
	            var alertPopup = $ionicPopup.alert({
	            	title: 'Error 500',
	              	template: 'Serve Error! The checkout will not be processed!',
	            });
          	}
	      	if(response.status==400){
	      		$scope.isEmpty = true;
	      	}
	      	else{$scope.isEmpty=false;}
	      	/* Fim Tratamento de erros*/
	      	$scope.hideLoading();
         	console.log(response);
        });
    	
  	});
  	var SenderHash;
  	$scope.method = 0;
  	$scope.user = {};
  	var checkoutData={};

  	$scope.showLoading = function() {
	    $ionicLoading.show({
	      template: '<ion-spinner></ion-spinner>'
	    }).then(function(){
	       console.log("The loading indicator is now displayed");
	    });
	  };
	  $scope.hideLoading = function(){
	    $ionicLoading.hide().then(function(){
	       console.log("The loading indicator is now hidden");
	    });
	  };	

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
		$scope.showLoading();
		var param = {
			cardNumber: $scope.user.cardnumber,
			cardBin:  $scope.user.cardnumber.slice(0,6),
			cvv: $scope.user.cvv,
			expirationMonth: $scope.user.expirationMonth,
			expirationYear: $scope.user.expirationYear,
			success: function(response) {
				//token gerado, esse deve ser usado na chamada da API do Checkout Transparente
				var cart = angular.fromJson($window.localStorage ['cart']);
				checkoutData.cart = JSON.parse(cart);
				checkoutData.cpf = '15600944276'; //valid teste cpf 15600944276
				checkoutData.creditCardToken = response.card.token;
				checkoutData.name = $scope.user.name;
				checkoutData.SenderHash = PagSeguroDirectPayment.getSenderHash();

				paymentCheckout.creditCardCheckout(checkoutData).then(function successCallback(response) {
		 			console.log(response.data);
		 			$scope.hideLoading();
		              var alertPopup = $ionicPopup.alert({
		                title: 'Finalizado',
		                template: 'Sua compra foi efetuada com sucesso!',
		              });
		              $scope.user = {};		       
        		}, function errorCallback(response) {
        			$scope.hideLoading();
        			var alertPopup = $ionicPopup.alert({
		                title: 'Error 401',
		                template: 'Alguma coisa deu errado!',
		              });
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
			$scope.hideLoading();

			//if(Object.keys(response.errors)==1000)
			console.log(response);
			var error = Object.keys(response.errors);
			
			switch(error[0]) {
			    case '10000':
			         alertPopup = $ionicPopup.alert({
		                title: 'Error 10000',
		                template: 'Numero do cartão inválido',
		            });
			        break;
			    case '10001' :
			         alertPopup = $ionicPopup.alert({
		                title: 'Error 10001',
		                template: 'Numero do cartão inválido',
		            });
			        break;
		        case '30405' :
		         alertPopup = $ionicPopup.alert({
	                title: 'Error 30405',
	                template: 'Data do cartão invalida',
	            });
		        break;
			    default: 
			         alertPopup = $ionicPopup.alert({
		                title: 'Error 1000',
		                template: 'Alguma coisa deu errado',
		            });
			}



			 
        			
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