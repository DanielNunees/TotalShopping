(function() {
    'use strict';
	angular.module('app')
	.controller('checkoutController', ['$scope','$ionicNavBarDelegate','ngCart','cartFactory','checkoutFactory','userFactory','$ionicActionSheet',  function($scope,$ionicNavBarDelegate,ngCart,cartFactory,checkoutFactory,userFactory,$ionicActionSheet){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
    	$ionicNavBarDelegate.showBackButton(true);
    	
    	checkoutFactory.getSession().then(function successCallback(response) {
	  		//PagSeguroDirectPayment.setSessionId(response.data);
	  		var SenderHash = PagSeguroDirectPayment.getSenderHash();
        },function errorCallback(response) {
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
         	console.log(response);
        });
  	});

  	$scope.method = 1;

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
	     	checkoutFactory.resetSessionId();
	     	checkoutFactory.getSession();
	     	$scope.method = index;
	       return true;
	     }
	   });
	 }; 

	    $scope.boletoCheckout = function(user){
	    	console.log('aqui foi');
			checkoutFactory.boletoCheckout(user).then(function successCallback(response){
			  ngCart.empty();
			  delete $scope.user;
			  checkoutFactory.resetSessionId();
			  console.log(response.data);
			  checkoutFactory.getSession().then(function successCallback(response){
			    console.log(response);
			  });

			},function errorCallback(response){
			  console.log(response);
			});
		}


		$scope.creditCardCheckout = function(user){
		    var param = {
		      cardNumber: user.cardnumber,
		      cardBin:  user.cardnumber.slice(0,6),
		      cvv: user.cvv,
		      expirationMonth: user.expirationMonth,
		      expirationYear: user.expirationYear,
		      success: function(response) {
		        //token gerado, esse deve ser usado na chamada da API do Checkout Transparente
		        var checkoutData = {};
		        checkoutData.creditCardToken = response.card.token;
		        
		        checkoutData.cpf = '15600944276'; //valid teste cpf 15600944276
		        checkoutData.name = user.name;
		        checkoutData.SenderHash = PagSeguroDirectPayment.getSenderHash();

		        checkoutFactory.creditCardCheckout(checkoutData).
		          then(function successCallback(response) {
		          checkoutFactory.resetSessionId();
		          ngCart.empty();
		          delete $scope.user;
		          console.log(response);
		        },function errorCallback(response) {
		          console.log(response);
		      	});
		      },
		      error: function(response) {
		        //tratamento do erro

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
		    param.cardBin = user.cardnumber.slice(0,6);
		    PagSeguroDirectPayment.createCardToken(param);
  		}

	var carregarDadosDoUsuario = function(){
  		userFactory.loadUserData().then(function(response) {
			console.log(response);
			$scope.user = {};
			$scope.address = {};

			$scope.user = {firstname: response.user[0]['firstname'], lastname: response.user[0]['lastname'],
						   birthday: response.user[0]['birthday'], email: response.user[0]['email']};
			
			if(!angular.isUndefined(response.address)){
				$scope.address = {address: response.address[0]['address'], address1: response.address[0]['address1'],
								  address2: response.address[0]['address2'], city: response.address[0]['city'],
								  postcode: response.address[0]['postcode'], state: response.address[0]['state'],
								  phoneMobile: response.address[0]['phone_mobile'], other: response.address[0]['other'] };
			}
	    $scope.isEmpty = false;
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
  	
  	carregarDadosDoUsuario();
		
	}]);
})();