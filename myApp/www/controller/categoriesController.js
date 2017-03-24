(function() {
    'use strict';
	angular.module('app')
	.controller('categoriesController', ['$scope','$ionicNavBarDelegate','$stateParams','productCategoryFactory','$ionicTabsDelegate', function($scope,$ionicNavBarDelegate,$stateParams,productCategoryFactory,$ionicTabsDelegate){
		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	      $ionicTabsDelegate.select(4);
	    });

		productCategoryFactory.getCategories().then(function(response){
			$scope.categories = [];

			angular.forEach(response.data, function(value,key){
				$scope.categories.push({name:value['name'],
										categoryId:value['id_category']

				});
			})
			
		});
	}]);
})();