(function() {
    'use strict';
	angular.module('app')
	.controller('checkoutController', ['$scope','$ionicNavBarDelegate','ngCart','cartFactory','checkoutFactory','userFactory','$ionicActionSheet','alertsFactory','$auth', function($scope,$ionicNavBarDelegate,ngCart,cartFactory,checkoutFactory,userFactory,$ionicActionSheet,alertsFactory,$auth){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
    	$ionicNavBarDelegate.showBackButton(true);
    	if($auth.isAuthenticated()){
    		carregarDadosDoUsuario();
    	}
    	checkoutFactory.getSession().then(function successCallback(response) {
	  		//PagSeguroDirectPayment.setSessionId(response.data);
	  		var SenderHash = PagSeguroDirectPayment.getSenderHash();
	  		console.log(SenderHash);
        },function errorCallback(response) {
        	/* Tratamento de erros*/
	      	//error 400 - No content

	      	alertsFactory.showAlert("Error 500","Server Error! The checkout will not be processed!");
	      	if(response.status == 500){
	      		alertsFactory.showAlert("Error 500",'Server Error! The checkout will not be processed!' );
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
  	$scope.boletoData = {};


  	$scope.getPaymentMethods = function(){
  		PagSeguroDirectPayment.getPaymentMethods({
		    amount: 5.00,
		    success: function(response) {
		        //meios de pagamento disponíveis
		        console.log(response);
		    },
		    error: function(response) {
		        //tratamento do erro
		        console.log(response);
		    },
		    complete: function(response) {
		        //tratamento comum para todas chamadas
		        console.log(response);
		    }
		});

  	}


  	$scope.getBrand = function(user){
  		 PagSeguroDirectPayment.getBrand({
			 cardBin: user.cardnumber.slice(0,6),
			 success: function(response) {
			 //brand found
			 console.log(response);
			 },
			 error: function(response) {
			 //error handling
			 },
			 complete: function(response) {
			 //optional handling for both results
			 }
	 	});
  	}
  	





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
	 var x2js = new X2JS();

	var convertXml2JSon = function (data) {
	    var x2js = new X2JS();
	    var aftCnv = x2js.xml_str2json(data);
	    return aftCnv;
	}

	    $scope.boletoCheckout = function(boletoData){
	    	
	    	var SenderHash = PagSeguroDirectPayment.getSenderHash();
	    	boletoData.SenderHash = SenderHash;
	    	console.log(boletoData);
			checkoutFactory.boletoCheckout(boletoData).then(function successCallback(response){
				console.log(response.status);
			  ngCart.empty();
			  delete $scope.user;
			  checkoutFactory.resetSessionId();
			  console.log((response.data));
			},function errorCallback(response){
			  console.log(convertXml2JSon(response.data));
			  ngCart.empty();
			  delete $scope.user;
			  console.log(response.data);
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
		        console.log(checkoutData.creditCardToken);

		        checkoutData.cpf = user.cpf; //valid teste cpf 15600944276
		        checkoutData.name = user.name;
		        checkoutData.SenderHash = PagSeguroDirectPayment.getSenderHash();

		        checkoutFactory.creditCardCheckout(checkoutData).
		          then(function successCallback(response) {
		          checkoutFactory.resetSessionId();
		          ngCart.empty();
		          //delete $scope.user;
		          console.log(response);
		        },function errorCallback(response) {
		          console.log(response);
		          console.log(convertXml2JSon(response.data));
		      	});
		      },
		      error: function(response) {
		        //tratamento do erro
		        console.log(response);
		        console.log(convertXml2JSon(response.data));
		        //if(Object.keys(response.errors)==1000)
		        //console.log(response);
		        ngCart.empty();
			  	//delete $scope.user;
		        //var error = Object.keys(response.errors);
		        
		        /*switch(error[0]) {
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
		        } */
		      },
		      complete: function(response) {
		      //tratamento comum para todas chamadas
		      }
		    }
		    //parâmetro opcional para qualquer chamada
		    param.cardBin = user.cardnumber.slice(0,6);
		    PagSeguroDirectPayment.createCardToken(param);
  		}

  	if($auth.isAuthenticated())
	var carregarDadosDoUsuario = function(){
  		userFactory.loadUserData().then(function(response) {
			console.log(response);
			$scope.user = {};
			$scope.address = {};

			$scope.user = {firstname: response.user['firstname'], lastname: response.user['lastname'],
						   birthday: response.user['birthday'], email: response.user['email']};
			
			if(!angular.isUndefined(response.address)){
				$scope.address = {address: response.address['address'], address1: response.address['address1'],
								  address2: response.address['address2'], city: response.address['city'],
								  postcode: response.address['postcode'], state: response.address['state'],
								  phoneMobile: response.address['phone_mobile'], other: response.address['other'] };
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
  	
  	
		
	}]);
})();