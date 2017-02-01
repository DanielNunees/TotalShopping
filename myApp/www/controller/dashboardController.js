(function() {
    'use strict';
angular.module('app')
.controller('dashboardController', ['$scope','$auth','$state','$ionicSlideBoxDelegate','$ionicModal','$ionicNavBarDelegate','userFactory','$ionicPopup','dashboardFactory', function($scope,$auth,$state,$ionicSlideBoxDelegate,$ionicModal,$ionicNavBarDelegate,userFactory,$ionicPopup,dashboardFactory){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
		$ionicNavBarDelegate.showBackButton(true);
		$ionicSlideBoxDelegate.slide(0, [0]);
    	$scope.slide = 0;
  	});

	$scope.address = {};

	$scope.isAuthenticated = $auth.isAuthenticated();

	var CarregarHistoricoDeCompras = function(){
		dashboardFactory.loadHistoric().then(function(data){
				$scope.products = [];
				angular.forEach(data,function(value,key){
					//console.log(value);
					$scope.products[key] = {
						name: value[0]['id_order_detail'],
						items:[]
					};
					angular.forEach(value,function(value1,key1){
						$scope.products[key].items.push({productPrice:value1['product']['description'][0]['product_price']['price'],
										      productName:value1['product']['description'][0]['name'],
										      productQuantity: value1['product_quantity'],
										      productImage: value1['product']['images'][0]['image'],
										      productAttribute: value1['product']['attributes'][0]['name'],
										      productId: value1['product_id']
										      });
		        	})
				})
		},function errorCallback(data){
			console.log(data);
		});
	}

	CarregarHistoricoDeCompras();
	  /*
	   * if given group is the selected group, deselect it
	   * else, select the given group
	   */
	  $scope.toggleGroup = function(group) {
	    if ($scope.isGroupShown(group)) {
	      $scope.shownGroup = null;
	    } else {
	      $scope.shownGroup = group;
	    }
	  };
	  
	  $scope.isGroupShown = function(group) {
	    return $scope.shownGroup === group;
	  };

	$scope.logout = function(){
		$auth.logout();
		$state.go('home');
		//$ionicHistory.removeBackView();
	}

	$scope.slideChanged = function(index) {
		$ionicSlideBoxDelegate.slide(index, [300]);
	};

	$scope.slideHasChanged = function(index){
		$scope.slide = $ionicSlideBoxDelegate.currentIndex();
	}

	var carregarDadosDoUsuario = function(){
		userFactory.loadUserData().then(function(response) {
			$scope.user = {};
			$scope.address = {};

			$scope.user = {firstname: response.user[0]['firstname'], lastname: response.user[0]['lastname'],
						   birthday: response.user[0]['birthday'], email: response.user[0]['email']};
			
			if(!angular.isUndefined(response.address)){
				$scope.address = {address: response.address[0]['address'], address1: response.address[0]['address1'],
								  address2: response.address[0]['address2'], city: response.address[0]['city'],
								  postcode: response.address[0]['postcode'], state: response.address[0]['state'],
								  phoneMobile: response.address[0]['phone_mobile'] };
			}

			$scope.states = response.states;
		    
		    $scope.isEmpty = false;
		}, function errorCallback(response) {
		       	/* Tratamento de erros*/
		       	switch (response.status) {
				    case 400:
			        	alertPopup = $ionicPopup.alert({
	                      title: 'Error 400',
	                      template: 'Nenhum endereço cadastrado',
	                  	});
		                break; 
				    case 422:
				        alertPopup = $ionicPopup.alert({
	                      title: 'Error 422',
	                      template: 'Paramentros errados',
	                  	});
		                break; 
				    default: 
				        alertPopup = $ionicPopup.alert({
	                      title: 'Error',
	                      template: 'Algo deu errado',
	                  	});
		                break;
				}
		         	console.log(response);
	        });
		};
		carregarDadosDoUsuario();

	$scope.updateOrCreateAddress = function(address){
		address.address1 = address.address1+','+address.number;
		userFactory.updateOrCreateAddress(address).then(function successCallback(response) {
	      	console.log(response.data);
	      	$scope.modal.hide();
	      	$scope.address = {};
	      	if(!angular.isUndefined(response.address)){
			$scope.address = {address1: response.address[0]['address1'],
							  address2: response.address[0]['address2'], city: response.address[0]['city'],
							  postcode: response.address[0]['postcode'], state: response.address[0]['state'],
							  phoneMobile: response.address[0]['phone_mobile'] };
			}

	        }, function errorCallback(response) {
		       	/* Tratamento de erros*/
		      	//error 400 - No content
		      	if(response.status==400){
		      		$scope.isEmpty = true;
		      	}
		      	else{$scope.isEmpty=false;}
		      	/* Fim Tratamento de erros*/
		         	console.log(response);
	        });
	} 
	$ionicModal.fromTemplateUrl('view/userAddressRegisterModal.html', {
      scope: $scope,
      animation: 'slide-in-up',

	  }).then(function(modal) {
	    $scope.modal = modal;
	  });

	  $scope.openModal = function() {
	    $scope.modal.show();
	  };

	  $scope.closeModal = function() {
	    $scope.modal.hide();
	  };
	  // Cleanup the modal when we're done with it!
	  $scope.$on('$destroy', function() {
	    $scope.modal.remove();
	  });
	  // Execute action on hide modal
	  $scope.$on('modal.hidden', function() {
	    // Execute action
	  });
	  // Execute action on remove modal
	  $scope.$on('modal.removed', function() {
	    // Execute action
	  });

}]);
})();