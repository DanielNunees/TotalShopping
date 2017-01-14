(function() {
    'use strict';
angular.module('app')
.controller('userDashboardController', ['$scope','$auth','$location','$ionicHistory','$ionicSlideBoxDelegate','$http','$httpParamSerializerJQLike','$ionicModal','$ionicNavBarDelegate','userDataFactory','$ionicPopup', function($scope,$auth,$location,$ionicHistory,$ionicSlideBoxDelegate,$http,$httpParamSerializerJQLike,$ionicModal,$ionicNavBarDelegate,userDataFactory,$ionicPopup){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
		$ionicNavBarDelegate.showBackButton(true);
		$ionicSlideBoxDelegate.slide(0, [0]);
    	$scope.loadData();
    	$scope.slide = 0;
  	});

	$scope.address = {};

	$scope.isAuthenticated = function() {
	  return $auth.isAuthenticated();
	};

	userDataFactory.loadHistoric().then(function(data){
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
		$location.url('/user/home');
		userDataFactory.resetUserData();
		//$ionicHistory.removeBackView();
	}

	$scope.slideChanged = function(index) {
		$ionicSlideBoxDelegate.slide(index, [300]);
	};

	$scope.slideHasChanged = function(index){
		$scope.slide = $ionicSlideBoxDelegate.currentIndex();
	}

	$scope.loadData = function(){
		if($auth.isAuthenticated()){
			userDataFactory.loadUserData().then(function(response) {
			    $scope.isEmpty = false;
	      		$scope.userData = response.address[0];
	      		$scope.userBirth = response.user[0];
	      		$scope.states = response.states;
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
		}
	}

	$scope.createAddress = function(){
		$scope.address.id_customer = localStorage.id;
		$scope.address.address1 =$scope.address.address1+','+$scope.address.number;
		userDataFactory.createAddress($scope.address).then(function successCallback(response) {
	      		$scope.modal.hide();
	      		$scope.address = {};
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

	$scope.updateAddress = function(){
		$scope.address.id_customer = localStorage.id;
		$scope.address.address1 =$scope.address.address1+','+$scope.address.number;
		userDataFactory.updateAddress($scope.address).then(function successCallback(response) {
	      	$scope.modal.hide();
	      	$scope.address = {};
	      	$scope.userData = response.data[0];

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
	    $scope.loadData();
	  };
	  // Cleanup the modal when we're done with it!
	  $scope.$on('$destroy', function() {
	    $scope.modal.remove();
	  });
	  // Execute action on hide modal
	  $scope.$on('modal.hidden', function() {
	    // Execute action
	    $scope.loadData();
	    $scope.address = {};
	  });
	  // Execute action on remove modal
	  $scope.$on('modal.removed', function() {
	    // Execute action
	    $scope.loadData();
	  });

}]);
})();