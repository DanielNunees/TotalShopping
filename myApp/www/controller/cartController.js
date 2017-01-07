(function() {
    'use strict';
	angular.module('app')
	.controller('cartController', ['$scope','$ionicNavBarDelegate','$http','$httpParamSerializerJQLike','ngCart','cartFactory',  function($scope,$ionicNavBarDelegate,$http,$httpParamSerializerJQLike,ngCart,cartFactory){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	    });
		$scope.ngCart = ngCart;
		$scope.cartFactory = cartFactory;
		$scope.data = {};

		$scope.remove =function(id_product,id_product_attribute){
			console.log(id_product, id_product_attribute);
			cartFactory.removeProduct(id_product,id_product_attribute).then(function successCallback(response){
				$scope.ngCart = ngCart;
		})
		} 
	
		cartFactory.loadCart().then(function successCallback(response) {
			var description = response.description;
			var images = response.images;
			var attributes = response.attributes;
			var i=0;
		
			ngCart.empty();
			angular.forEach(images, function(value, key) {
				var data={'image':value.image,'size':attributes[i].attributes.name, 'product_attributte':attributes[i].attributes.id_product_attribute};
				ngCart.addItem(description[i].id_product, description[i].name, description[i].product_price.price, attributes[i].quantity, data)
				i++;
			});
        },function errorCallback(response) {

        });		
	}]);
})();