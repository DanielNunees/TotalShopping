(function() {
    'use strict';
angular.module('app')
.controller('dashboardController', ['$scope','$auth','$state','$ionicSlideBoxDelegate','$ionicModal','$ionicNavBarDelegate','userFactory','dashboardFactory','$ionicHistory','$ionicScrollDelegate','userDataCacheFactory','wishlistFactory','$ionicTabsDelegate','ngCart', function($scope,$auth,$state,$ionicSlideBoxDelegate,$ionicModal,$ionicNavBarDelegate,userFactory,dashboardFactory,$ionicHistory,$ionicScrollDelegate,userDataCacheFactory,wishlistFactory,$ionicTabsDelegate,ngCart){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
		if(!$auth.isAuthenticated()){
			$state.go('userLogin');
		}
		else{
			carregarDadosDoUsuario();
			CarregarHistoricoDeCompras();
		}
		if($ionicHistory.backView() && $ionicHistory.backView().stateName.indexOf("userLogin")!=-1){
			$ionicHistory.removeBackView();
		}
 		$ionicTabsDelegate.select(2);
 		$ionicNavBarDelegate.showBackButton(true);
		$ionicSlideBoxDelegate.slide(0, [0]);
    	$scope.slide = 0;
  	});

	$scope.address = {};
	var CarregarHistoricoDeCompras = function(){
		dashboardFactory.loadHistoric().then(function(data){
			console.log(data);
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
		var cache = userDataCacheFactory.info();
	    if(cache.size>0){
	      loadUserDataFromCache(cache);
	      return;
	    }
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
				userDataCacheFactory.put(1,$scope.address);
				$scope.haveAddress = true;
			}
			userDataCacheFactory.put(0,$scope.user);
			$scope.states = response.data.states;
		}, function errorCallback(response) {
		       	/* Tratamento de erros*/
		         	console.log(response);
	        });
	};
	var loadUserDataFromCache = function(cache){
		$scope.user = {};
		$scope.user = userDataCacheFactory.get(0);
		if(!angular.isUndefined(userDataCacheFactory.get(1))){
			$scope.address = userDataCacheFactory.get(1); 
		}
	};
	
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
		console.log('logout');
		userDataCacheFactory.removeAll();
		wishlistFactory.removeAll();
		console.log(userDataCacheFactory.info());
		$auth.logout();
		$state.go('home');
		ngCart.empty();
		//$ionicHistory.removeBackView();
	}

	$scope.slideChanged = function(index) {
		$ionicSlideBoxDelegate.slide(index, [300]);
		$ionicScrollDelegate.scrollTop();	    
	};

	$scope.slideHasChanged = function(index){
		$scope.slide = $ionicSlideBoxDelegate.currentIndex();
		$ionicScrollDelegate.scrollTop();	    
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