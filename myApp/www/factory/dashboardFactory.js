(function() {
    'use strict';
angular.module('app')
.factory('dashboardFactory', function($http,valueConfig){
	return{
		loadHistoric: function(){
			return $http({
				method: 'GET',
				cache: true,
				url: valueConfig.baseUrl+'/historic/getHistoric'
			})
		}

	}

});
})();