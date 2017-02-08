(function() {
    'use strict';
	angular.module('app')
	.controller('storeController', ['$scope','$ionicNavBarDelegate','multiStoreFactory','$stateParams',  function($scope,$ionicNavBarDelegate,multiStoreFactory,$stateParams){

		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	    });

		$scope.products = [];
		$scope.endData = true;
		var pageCache = [];

		$scope.getProducts = function(){
			multiStoreFactory.getProducts($stateParams.idStore).
		      then(function successCallback(data){
		      	console.log(data);
		        multiStoreFactory.nextPage();
		        pageCache[$stateParams.idStore] = multiStoreFactory.getPage();
		        multiStoreFactory.setPage(pageCache[$stateParams.idStore]);
		        console.log(multiStoreFactory.getPage());
		        if(data['products'].length>0)
		        angular.forEach(data['products'], function(value1, key1) {
	            $scope.products.push({productPrice:value1['description'][0]['product_price']['price'],
	                            productName:value1['description'][0]['name'],
	                            productImages: value1['images'][0]['image'],
	                            productId: value1['description'][0]['id_product']
	                            });
	        	})
		    console.log($scope.products.length);
		        if($scope.products.length>=data['max'])
		          $scope.endData = false;
		        $scope.$broadcast('scroll.infiniteScrollComplete');
		      },function errorCallback(response){
		        console.log(response);
		  });
		}
	}]);
})();