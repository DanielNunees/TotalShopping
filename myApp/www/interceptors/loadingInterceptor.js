(function() {
    'use strict';
angular.module('app')
.factory('loadingInterceptor', ['$q','$rootScope',function($q, $rootScope){
	var activeRequests = 0;
	 var started = function() {
	   if(activeRequests==0) {

	     $rootScope.$broadcast('loadingStatusActive');
	   }    
	   activeRequests++;
	  };
	  var ended = function() {
	  	if(activeRequests>0){
	   		activeRequests--;
	   		//console.log(activeRequests);
	  	}
	   if(activeRequests==0) {
	   	//console.log(activeRequests);
	     $rootScope.$broadcast('loadingStatusInactive');
	    }
	
	  };
	return{
		request: function (config){

			var url = config.url;
			if(url.indexOf('127.0.1.1') > -1){
				started();
				$rootScope.loading = true;
			}
			return config || $q.when(config);
		},
		requestError: function(rejection){
			$rootScope.loading = false;
			ended();
			return $q.reject(rejection);
		},
		response: function(response){
			if(angular.isObject(response.data)){
				console.log(response.data);
				$rootScope.loading = false;
				ended();
			}
			return response;
		},
		responseError: function(rejection){
			$rootScope.loading = false;
			ended();
			return $q.reject(rejection);
		}
	};
}]);
})();