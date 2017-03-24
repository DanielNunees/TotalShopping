(function() {
    'use strict';
	angular.module('app')
	.factory('productCategoryFactory', function($http,valueConfig){
		return {
			getCategories: function(){
				return $http({
					method: 'GET',
					cache: true,
					url: valueConfig.baseUrl+'/product/categories'
				});
			},

			getProducts: function(category){
				return $http({
					method:"GET",
					cache: true,
					url: valueConfig.baseUrl+'/product/category/'+category
				});
			}
		}
	});
})();