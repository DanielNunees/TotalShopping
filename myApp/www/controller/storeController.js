(function() {
    'use strict';
	angular.module('app')
	.controller('storeController', ['$scope','$ionicNavBarDelegate','multiStoreFactory','$stateParams',  function($scope,$ionicNavBarDelegate,multiStoreFactory,$stateParams){

		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	    });

		$scope.products = [];
		$scope.endData = true;
		multiStoreFactory.setPage(1);

		$scope.getProducts = function(){
			console.log(multiStoreFactory.getPage());
			multiStoreFactory.getProducts($stateParams.idStore).
		      then(function successCallback(data){
		        multiStoreFactory.nextPage();
		        angular.forEach(data.data['products'], function(value1, key1) {
	            	$scope.products.push({productPrice:value1['product_price']['price'],
	                            		  productName:value1['name'],
	                            		  productImages: value1['image'][0],
	                            		  productId: value1['id_product']
	                            		});
	        	})
		        if($scope.products.length>=data.data.max){
		        	$scope.endData = false;
		        	
		        }
		        $scope.$broadcast('scroll.infiniteScrollComplete');
		      },function errorCallback(response){
		        console.log(response);
		  });
		}
	}]);
})();