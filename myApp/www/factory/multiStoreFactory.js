(function() {
    'use strict';
	angular.module('app')
	.factory('multiStoreFactory', function($http){
		var id_product;
		return {
			getProducts: function(idStore){
				return $http({
			        method: 'GET',
			        url: 'http://127.0.1.1/laravel/public/multistore/store/'+idStore
	      		}).then(function(response){
	      			return(response.data);
			    }); 
			}
		}
	});
})();