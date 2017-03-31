(function() {
    'use strict';
angular.module('app')
.factory('dashboardFactory', function($http,valueConfig,historicCacheFactory){
	return{
		loadHistoric: function(){
			return $http({
				method: 'GET',
				cache: historicCacheFactory,
				url: valueConfig.baseUrl+'/historic/getHistoric'
			})
		}

	}

});
})();