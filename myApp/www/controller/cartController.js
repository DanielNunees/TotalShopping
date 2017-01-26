(function() {
    'use strict';
	angular.module('app')
	.controller('cartController', ['$scope','$ionicNavBarDelegate','ngCart','cartFactory','$auth','$ionicLoading',  function($scope,$ionicNavBarDelegate,ngCart,cartFactory,$auth,$ionicLoading){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);	
	    });
		$scope.ngCart = ngCart;
		$scope.data = {};
		var count=0;

		$scope.isAuthenticated = $auth.isAuthenticated();

		$scope.remove =function(id_product,id_product_attribute){
			cartFactory.removeProduct(id_product,id_product_attribute).
				then(function successCallback(response){
					$scope.ngCart = ngCart;
				}, function errorCallback(response){
					console.log(response);
				});
		} 
		if($auth.isAuthenticated())
		cartFactory.loadCart().then(function successCallback(response) {
			ngCart.empty();
			console.log(response);
			if(response.length>0)
			angular.forEach(response, function(value, key) {
				var data={'image':value['product']['images'][0]['image'] ,'size':value['product']['attributes'][0]['name'] , 'product_attributte':value['product']['attributes'][0]['id_product_attribute']};
				ngCart.addItem(value.id_product, value['product']['description'][0]['name'] , value['product']['description'][0]['product_price']['price'], value.quantity, data)
			});
        },function errorCallback(response) {
        	console.log(response);
        });
	}]);
})();