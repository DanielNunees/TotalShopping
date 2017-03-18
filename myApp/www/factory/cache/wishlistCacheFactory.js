(function() {
    'use strict';
	angular.module('app').
	factory('wishlistCacheFactory', ['$cacheFactory', function($cacheFactory) {
    	return $cacheFactory('wishlist');
  	}]);
})();