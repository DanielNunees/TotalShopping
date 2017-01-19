(function() {
    'use strict';
	angular.module('app')
	.controller('multiStoreController', ['$scope','$ionicNavBarDelegate','multiStoreFactory','$stateParams',  function($scope,$ionicNavBarDelegate,multiStoreFactory,$stateParams){

		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	    });
		console.log($stateParams.idStore);

		multiStoreFactory.getProducts($stateParams.idStore).then( function successCallback(data){
			$scope.products = [];
			console.log(data);

				angular.forEach(data,function(value,key){
					$scope.products.push({productPrice:value['description'][0]['product_price']['price'],
									      productName:value['description'][0]['name'],
									      productImage: value['images'][0]['image'],
									      productId: value['product_id']
									      });
	        	})
		},function errorCallback(response){

		});


	}]);
})();