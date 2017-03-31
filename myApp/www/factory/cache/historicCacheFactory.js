(function() {
    'use strict';
	angular.module('app').
	factory('historicCacheFactory', ['$cacheFactory', function($cacheFactory) {
    	return $cacheFactory('historic');
  	}]);
})();