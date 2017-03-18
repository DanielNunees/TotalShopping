(function() {
    'use strict';
	angular.module('app')
	.controller('multiStoreController', ['$scope','$ionicNavBarDelegate','multiStoreFactory','$stateParams',  function($scope,$ionicNavBarDelegate,multiStoreFactory,$stateParams){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	      multiStoreFactory.setPage(1);
	    });
		$scope.stores = [];
	    multiStoreFactory.getStores().then(function(data){
	    	console.log(multiStoreFactory.getPage());
	    	angular.forEach(data.data, function(value,key){
	    		$scope.stores.push({'store':value['id_shop'],
	    							'name':value['name']

	    		})
	    	});
	    })

	}]);
})();