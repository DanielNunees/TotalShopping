(function() {
    'use strict';
	angular.module('app')
	.factory('multiStoreFactory', function($http,valueConfig){
		var id_product;
		var page=1;
		return {
			getStores: function(){
				return $http({
					method: 'GET',
					cache: true,
					url: valueConfig.baseUrl+'/multistore/getStores'
				});
			},

			getProducts: function(idStore){
				return $http({
			        method: 'GET',
			        cache: true,
			        url: valueConfig.baseUrl+'/multistore/store/'+idStore+'/'+page
	      		}).then(function(response){
	      			return(response.data);
			    }); 
			},
			getPage: function(){
				return page;
			},

			nextPage: function(){
				return page++;
			},

			setPage: function(page){
				return page = page;
			}

		}
	});
})();