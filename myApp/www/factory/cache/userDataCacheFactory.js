(function() {
    'use strict';
	angular.module('app').
	factory('userDataCacheFactory', ['$cacheFactory', function($cacheFactory) {
    	return $cacheFactory('userData');
  	}]);
})();