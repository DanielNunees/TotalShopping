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
	            $scope.products.push({productPrice:value1['product_price']['price'],
	                            productName:value1['name'],
	                            productImages: value1['image'][0],
	                            productId: value1['id_product']
	                            });
	        	})
		});
	}]);
})();