(function() {
    'use strict';
	angular.module('app')
	.controller('checkoutController', ['$scope','$ionicNavBarDelegate','ngCart','cartFactory','checkoutFactory','userFactory','$ionicActionSheet','alertsFactory','$auth','loadingFactory','$state','userDataCacheFactory','$ionicModal', function($scope,$ionicNavBarDelegate,ngCart,cartFactory,checkoutFactory,userFactory,$ionicActionSheet,alertsFactory,$auth,loadingFactory,$state,userDataCacheFactory,$ionicModal){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
    	$ionicNavBarDelegate.showBackButton(true);
    	loadingFactory.show();
    	if($auth.isAuthenticated()){
    		carregarDadosDoUsuario();
    	}
    	checkoutFactory.getSession().then(function successCallback(response) {
	  		PagSeguroDirectPayment.setSessionId(response.data);
	  		checkoutFactory.setSession(response.data);
	  		var SenderHash = PagSeguroDirectPayment.getSenderHash();
	  		loadingFactory.hide();
        },function errorCallback(response) {
	      	alertsFactory.showAlert("Error 500","Server Error! The checkout will not be processed!");
         	console.log(response);
        });
  	});

  	$scope.method = 1;
  	$scope.boletoData = {};
  	$scope.user = {};
	$scope.address = {};
  	
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
    	if(verificacao()){
	    	loadingFactory.show();
	    	var SenderHash = PagSeguroDirectPayment.getSenderHash();
	    	boletoData.SenderHash = SenderHash;
			checkoutFactory.boletoCheckout(boletoData).then(function successCallback(response){
				loadingFactory.hide();
				ngCart.empty();
				delete $scope.boletoData;
				checkoutFactory.resetSessionId();
				alertsFactory.showAlert("Sucesso","Sua Compra Foi Feita Com Sucesso, A Confirmação do pagamento pode levar até 5 dias úteis!");
				$state.go("home");
			},function errorCallback(response){
		    	loadingFactory.hide();
				console.log(convertXml2JSon(response.data));
				ngCart.empty();
				delete $scope.user;
				alertsFactory.showAlert("Error","Algo de Errado Aconteceu :'(");
			});
		}
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
	        	var checkoutData = {creditCardToken:response.card.token,cpf:user.cpf,
	        						name:user.name,SenderHash:PagSeguroDirectPayment.getSenderHash()};

	        	checkoutFactory.creditCardCheckout(checkoutData).
	        	then(function successCallback(response) {
	          		alertsFactory.showAlert("Sucesso","Sua Compra Foi Feita Com Sucesso, A Confirmação do pagamento pode levar até 5 dias úteis!");
					$state.go("home");
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
	        	ngCart.empty();
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
		var cache = userDataCacheFactory.info();
	    if(cache.size>0){
	      loadUserDataFromCache(cache);
	      return;
	    }
		userFactory.loadUserData().then(function(response) {
			$scope.user = {firstname: response.data.user['firstname'], lastname: response.data.user['lastname'],
						   birthday: response.data.user['birthday'], email: response.data.user['email']};
			if(!angular.isUndefined(response.data.address)){
				$scope.address = {address: response.data.address['address'], address1: response.data.address['address1'],
								  address2: response.data.address['address2'], city: response.data.address['city'],
								  postcode: response.data.address['postcode'], state: response.data.address['state'],
								  phoneMobile: response.data.address['phone_mobile'] };
				userDataCacheFactory.put(1,$scope.address);
			}

			userDataCacheFactory.put(0,$scope.user);
			userDataCacheFactory.put(2,response.data.states);
		    $scope.isEmpty = false;
		},function errorCallback(response) {
		    /* Tratamento de erros*/
		    console.log(response);
	    });
	};
	var loadUserDataFromCache = function(){
		$scope.user = {};
		$scope.address = {};
		$scope.user = userDataCacheFactory.get(0);
		$scope.states = userDataCacheFactory.get(2);

		if(!angular.isUndefined(userDataCacheFactory.get(1))){
			$scope.address = userDataCacheFactory.get(1);
			console.log($scope.address);
		}
	};
	$scope.updateOrCreateAddress = function(address){
		address.address1 = address.address1+','+address.number;
		userFactory.updateOrCreateAddress(address).then(function successCallback(response) {
	      	console.log(response.data);
	      	$scope.address = {};
				$scope.address = {address1: response.data['address1'],
								  address2: response.data['address2'], 
								  city: response.data['city'],
								  postcode: response.data['postcode'], 
								  state: response.data['state'],
								  phoneMobile: response.data['phone_mobile']};
				userDataCacheFactory.put(1,$scope.address);
				$scope.modal.hide();
	        }, function errorCallback(response) {
		        console.log(response.data);
	        });
		loadUserDataFromCache();
	} 

	$ionicModal.fromTemplateUrl('view/userAddressRegisterModal.html', {
    	scope: $scope,
      	animation: 'slide-in-up'
	}).then(function(modal) {
		$scope.modal = modal;
	});

	var verificacao = function(){
		if($auth.isAuthenticated()){
			if(ngCart.getTotalItems()<=0){
				alertsFactory.showAlert("Seu Carrinho Está Vazio.");
				return false;
			}
			else if(angular.isUndefined(userDataCacheFactory.get(1))){
				$scope.states = userDataCacheFactory.get(2);
				alertsFactory.showAlert("Cadastre um endereço para a entrega.");
				$scope.modal.show();
				return false;
			}
			else return true;
		}
		else{
			$state.transitionTo('userLogin');
			return false;
		}
	}

	
		
	}]);
})();