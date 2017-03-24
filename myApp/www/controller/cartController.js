(function() {
    'use strict';
	angular.module('app')
	.controller('cartController', ['$scope','$ionicNavBarDelegate','ngCart','cartFactory','$auth','$ionicLoading','$state','alertsFactory', function($scope,$ionicNavBarDelegate,ngCart,cartFactory,$auth,$ionicLoading,$state,alertsFactory){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);	
	    });
		$scope.ngCart = ngCart;
		$scope.data = {};

		$scope.isAuthenticated = function(){
			return $auth.isAuthenticated();
		}

		$scope.finalizar = function(){
			if($auth.isAuthenticated()){
				if(ngCart.getTotalItems()>0)
					$state.transitionTo('checkout');
				else alertsFactory.showAlert("Seu Carrinho Está Vazio.")
			}
			else{
				$state.transitionTo('userLogin');
			}
		}

		$scope.remove =function(id_product,id_product_attribute){
			if(!$auth.isAuthenticated()){
				ngCart.removeItemById(parseInt(id_product),parseInt(id_product_attribute));
				return;
			}
			cartFactory.removeProduct(id_product,id_product_attribute).
				then(function successCallback(response){
					ngCart.removeItemById(parseInt(id_product),parseInt(id_product_attribute));
					$scope.ngCart = ngCart;
				}, function errorCallback(response){
					ngCart.removeItemById(parseInt(id_product),parseInt(id_product_attribute));
					console.log(response.data);
				});
		}
		
		if($auth.isAuthenticated()){
			cartFactory.loadCart().then(function successCallback(response) {
				ngCart.empty();
				console.log(response.data);
				if(response.data.length>0){
					angular.forEach(response.data, function(value, key) {
						var data={'image':value['image'][0],
								  'size':value['attributes'][0]['name'], 
								  'product_attributte':value['attributes'][0]['id_product_attribute']};

						ngCart.addItem(value.id_product, value['name'] , value['product_price']['price'], value.quantity, data)
					});
				}else $scope.empty = true;
	        },function errorCallback(response) {
	        	console.log(response.data);
	        });
		}
	}]);
})();