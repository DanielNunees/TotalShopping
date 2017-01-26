(function() {
    'use strict';
angular.module('app')
.factory('loadingInterceptor', function($q, $rootScope){
	return{
		request: function (config){
			
			var url = config.url;
			if(url.indexOf('127.0.1.1') > -1){ 
				$rootScope.loading = true;
				console.log(config.url);
			}
			return config;
		},
		requestError: function(rejection){
			$rootScope.loading = false;
			return $q.reject(rejection);
		},
		response: function(response){
			$rootScope.loading = false;
			return response;
		},
		responseError: function(rejection){
			$rootScope.loading = false;
			return $q.reject(rejection);
		}
	};
});
})();