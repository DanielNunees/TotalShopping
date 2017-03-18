(function() {
    'use strict';
	angular.module('app')
	.factory('multiStoreFactory', function($http,valueConfig){
		var _page=1;
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
			        url: valueConfig.baseUrl+'/multistore/store/'+idStore+'/'+_page
	      		});
			},
			getPage: function(){
				return _page;
			},

			nextPage: function(){
				_page++;
			},

			setPage: function(page){
				_page = page;
			}
		}
	});
})();