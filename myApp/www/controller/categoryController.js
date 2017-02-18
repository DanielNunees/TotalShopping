(function() {
    'use strict';
	angular.module('app')
	.controller('categoryController', ['$scope','$ionicNavBarDelegate','$stateParams','productCategoryFactory',  function($scope,$ionicNavBarDelegate,$stateParams,productCategoryFactory){

		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	    });

		productCategoryFactory.getProducts($stateParams.categoryId).then(function(response){
			$scope.products = [];
			console.log(response.data);
			angular.forEach(response.data, function(value1, key1) {
	            $scope.products.push({productPrice:value1['description'][0]['product_price']['price'],
	                            productName:value1['description'][0]['name'],
	                            productImages: value1['images'][0]['image'],
	                            productId: value1['description'][0]['id_product']
	                            });
	        	})
		});
	}]);
})();