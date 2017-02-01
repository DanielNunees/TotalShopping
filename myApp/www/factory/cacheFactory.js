(function() {
    'use strict';
	angular.module('app').
	factory('cacheFactory', ['$cacheFactory', function($cacheFactory) {
    	return $cacheFactory('wishlist');
  	}]);
})();