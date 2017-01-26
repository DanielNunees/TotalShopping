(function() {
    'use strict';
	angular.module('app')
	.factory('multiStoreFactory', function($http,valueConfig){
		var id_product;
		return {
			getProducts: function(idStore){
				return $http({
			        method: 'GET',
			        url: valueConfig.baseUrl+'/multistore/store/'+idStore
	      		}).then(function(response){
	      			return(response.data);
			    }); 
			}
		}
	});
})();