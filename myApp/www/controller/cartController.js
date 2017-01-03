(function() {
    'use strict';
	angular.module('app')
	.controller('cartController', ['$scope','$ionicNavBarDelegate','$http','$httpParamSerializerJQLike','ngCart','cartFactory',  function($scope,$ionicNavBarDelegate,$http,$httpParamSerializerJQLike,ngCart,cartFactory){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	    });
		$scope.ngCart = ngCart;
		$scope.cartFactory = cartFactory;
		cartFactory.loadCart().then(function successCallback(response) {
			var description = response.description;
			var images = response.images;
			var attributes = response.attributes;

			var i=0;
			for(i;i<response.description.length;i++){
				var data={'image':images[i].image,'size':attributes[i].attributes.name, 'product_attributte':attributes[i].attributes.id_product_attribute};
				ngCart.addItem(description[i].id_product, description[i].name, description[i].product_price.price, attributes[i].quantity, data)
			}
        },function errorCallback(response) {

        });

		
	}]);
})();