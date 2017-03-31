(function() {
    'use strict';
	angular.module('app')
	.controller('checkoutController', ['$scope','$ionicNavBarDelegate','ngCart','checkoutFactory','userFactory','$ionicActionSheet','alertsFactory','$auth','loadingFactory','$state','userDataCacheFactory','$ionicModal','checkoutErrosFactory', function($scope,$ionicNavBarDelegate,ngCart,checkoutFactory,userFactory,$ionicActionSheet,alertsFactory,$auth,loadingFactory,$state,userDataCacheFactory,$ionicModal,checkoutErrosFactory){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
    	$ionicNavBarDelegate.showBackButton(true);
    	loadingFactory.show();
    	if($auth.isAuthenticated()){
    		carregarDadosDoUsuario();
    		getSession();
    	}
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

	var getSession = function(){
		checkoutFactory.getSession().then(function successCallback(response) {
			PagSeguroDirectPayment.setSessionId(response.data);
			checkoutFactory.setSession(response.data);
			var SenderHash = PagSeguroDirectPayment.getSenderHash();
			loadingFactory.hide();
		},function errorCallback(response) {
			alertsFactory.showAlert("Error 500","Server Error! The checkout will not be processed!");
			console.log(response);
		});
	};

	var x2js = new X2JS();
	var convertXml2JSon = function (data) {
	    var x2js = new X2JS();
	    var aftCnv = x2js.xml_str2json(data);
	    return aftCnv;
	};

    $scope.boletoCheckout = function(boletoData){
    	if(verificacao()){
    		console.log("passou verificacao");
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
				console.log(response.data);
			},function errorCallback(response){
		    	loadingFactory.hide();
		    	var error_code = convertXml2JSon(response.data);
				console.log(error_code);
				checkoutErrosFactory.error((error_code));
				//ngCart.empty();
				delete $scope.user;
			});
		}
	};


	$scope.creditCardCheckout = function(user){
		console.log(user.cardnumber);
		console.log(user.cardnumber.slice(0,6));
		console.log(user.cvv);
		console.log(user.expirationMonth);
		console.log(user.expirationYear);
		loadingFactory.show();
	    var param = {
	    	cardNumber: user.cardnumber,
	      	//cardBin:  user.cardnumber.slice(0,6),
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
	          		loadingFactory.hide();
	          		console.log(response);
	        	},function errorCallback(response) {
	         		console.log(response);
	          		console.log(convertXml2JSon(response.data));
	          		checkoutErrosFactory.error(convertXml2JSon(response.data));	
	          		loadingFactory.hide();
	      		});
	      	},
	      	error: function(response) {
	        	//tratamento do erro
	        	console.log(response);
	        	checkoutErrosFactory.creditCardError(response);
	        	checkoutFactory.resetSessionId();
	        	ngCart.empty();
	        	loadingFactory.hide();
	      	},
	      	complete: function(response) {
	      	//tratamento comum para todas chamadas
	      	}
	    }
	    //parâmetro opcional para qualquer chamada
	    param.cardBin = user.cardnumber.slice(0,6);
	    PagSeguroDirectPayment.createCardToken(param);
	};

  	var carregarDadosDoUsuario = function(){
		var cache = userDataCacheFactory.info();
	    if(cache.size>0){
	      loadUserDataFromCache();
	      return;
	    }
		userFactory.loadUserData().then(function(response) {

			$scope.states = response.data.states;

			$scope.user = {firstname: response.data.user['firstname'], lastname: response.data.user['lastname'],
						   birthday: response.data.user['birthday'], email: response.data.user['email']};
			if(!angular.isUndefined(response.data.address)){
				$scope.address = {address: response.data.address['address'], address1: response.data.address['address1'],
								  address2: response.data.address['address2'], city: response.data.address['city'],
								  postcode: response.data.address['postcode'], state: response.data.address['state'],
								  phoneMobile: response.data.address['phone_mobile'] };
				userDataCacheFactory.put(1,$scope.address);
				$scope.hasAddress = true;
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
		console.log('from cache');
		$scope.user = {};
		$scope.user = userDataCacheFactory.get(0);
		$scope.states = userDataCacheFactory.get(2);
		if(!angular.isUndefined(userDataCacheFactory.get(1))){
			$scope.address = userDataCacheFactory.get(1);
			$scope.hasAddress = true;
			console.log($scope.address);
		}
	};
	$scope.updateOrCreateAddress = function(address){
		address.address1 = address.address1+','+address.number;
		userFactory.updateOrCreateAddress(address).then(function successCallback(response) {
	      	console.log(response.data);
	      	$scope.address = {};
			$scope.address = {address: response.data.address['address'], address1: response.data.address['address1'],
								  address2: response.data.address['address2'], city: response.data.address['city'],
								  postcode: response.data.address['postcode'], state: response.data.address['state'],
								  phoneMobile: response.data.address['phone_mobile'] };
				userDataCacheFactory.put(1,$scope.address);
				$scope.hasAddress = true;
				$scope.modal.hide();
				loadUserDataFromCache();
	        },function errorCallback(response) {
		    	console.log(response.data);
	        });
		
	}; 

	$ionicModal.fromTemplateUrl('view/userAddressRegisterModal.html', {
    	scope: $scope,
      	animation: 'slide-in-up',
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
				//$scope.states = userDataCacheFactory.get(2);
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
	};

	
		
	}]);
})();