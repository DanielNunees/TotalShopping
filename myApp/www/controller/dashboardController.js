(function() {
    'use strict';
angular.module('app')
.controller('dashboardController', ['$scope','$auth','$state','$ionicSlideBoxDelegate','$ionicModal','$ionicNavBarDelegate','userFactory','$ionicPopup','dashboardFactory','$ionicHistory','$ionicScrollDelegate','$timeout', function($scope,$auth,$state,$ionicSlideBoxDelegate,$ionicModal,$ionicNavBarDelegate,userFactory,$ionicPopup,dashboardFactory,$ionicHistory,$ionicScrollDelegate,$timeout){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
		if(!$auth.isAuthenticated()){
			$state.go('home');
		}
		if($ionicHistory.backView() && $ionicHistory.backView().stateName.indexOf("userLogin")!=-1){
			$ionicHistory.removeBackView();
		}


		$ionicNavBarDelegate.showBackButton(true);
		$ionicSlideBoxDelegate.slide(0, [0]);
    	$scope.slide = 0;
  	});

	$scope.address = {};

	$scope.isAuthenticated = $auth.isAuthenticated();

	var CarregarHistoricoDeCompras = function(){
		dashboardFactory.loadHistoric().then(function(data){
				$scope.products = [];
				var i=0;
				angular.forEach(data.data,function(value,key){
					//console.log(value);
					$scope.products[i] = {
						name: key,
						items:[]
					};
					angular.forEach(value.products,function(value1,key1){

						$scope.products[i].items.push({productPrice:value1['product']['product_price']['price'],
										      productName:value1['product']['name'],
										      productQuantity: value1['product_quantity'],
										      productImage: value1['product']['image'][0],
										      productAttribute: value1['product']['attributes']['name'],
										      productId: value1['product']['id_product'],
										      reference: value['reference'],
										      state: value['state']
										      });
		        	})
		        	i++;
				})
		},function errorCallback(data){
			console.log(data.data);
		});
	}

	var carregarDadosDoUsuario = function(){
		userFactory.loadUserData().then(function(response) {
			$scope.user = {};
			$scope.address = {};
			$scope.user = {firstname: response.data.user['firstname'], lastname: response.data.user['lastname'],
						   birthday: response.data.user['birthday'], email: response.data.user['email']};
			
			if(!angular.isUndefined(response.data.address)){
				$scope.address = {address: response.data.address['address'], address1: response.data.address['address1'],
								  address2: response.data.address['address2'], city: response.data.address['city'],
								  postcode: response.data.address['postcode'], state: response.data.address['state'],
								  phoneMobile: response.data.address['phone_mobile'] };
			}

			$scope.states = response.data.states;
		    
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
	if($auth.isAuthenticated()){
		carregarDadosDoUsuario();
		CarregarHistoricoDeCompras();
	}
	
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
		$ionicScrollDelegate.scrollTop();	    
	    $timeout( function() {
	      $ionicScrollDelegate.resize();
	    }, 50);
	    $ionicScrollDelegate.resize();
	};

	$scope.slideHasChanged = function(index){
		$scope.slide = $ionicSlideBoxDelegate.currentIndex();
		$ionicScrollDelegate.scrollTop();	    
	    $timeout( function() {
	      $ionicScrollDelegate.resize();
	    }, 50);
	    $ionicScrollDelegate.resize();
	}

	$scope.updateOrCreateAddress = function(address){
		address.address1 = address.address1+','+address.number;
		userFactory.updateOrCreateAddress(address).then(function successCallback(response) {
	      	console.log(response.data);
	      	$scope.modal.hide();
	      	$scope.address = {};
	      	if(!angular.isUndefined(response.data.address)){
			$scope.address = {address1: response.data.address[0]['address1'],
							  address2: response.data.address[0]['address2'], city: response.data.address[0]['city'],
							  postcode: response.data.address[0]['postcode'], state: response.data.address[0]['state'],
							  phoneMobile: response.data.address[0]['phone_mobile'] };
			}

	        }, function errorCallback(response) {
		       	/* Tratamento de erros*/
		      	//error 400 - No content
		      	if(response.data.status==400){
		      		$scope.isEmpty = true;
		      	}
		      	else{$scope.isEmpty=false;}
		      	/* Fim Tratamento de erros*/
		         	console.log(response.data);
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