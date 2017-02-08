(function() {
    'use strict';
	angular.module('app')
	.controller('multiStoreController', ['$scope','$ionicNavBarDelegate','multiStoreFactory','$stateParams',  function($scope,$ionicNavBarDelegate,multiStoreFactory,$stateParams){

		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	    });
		$scope.stores = [];
	    multiStoreFactory.getStores().then(function(data){
	    	console.log(data.data);
	    	angular.forEach(data.data, function(value,key){
	    		$scope.stores.push({'store':value['id_shop'],
	    							'name':value['name']

	    		})
	    	});
	    })

	}]);
})();