(function() {
    'use strict';
	angular.module('app')
	.factory('loadingFactory', ['$ionicLoading',function($ionicLoading){
		return {
		    show: function() {
		      $ionicLoading.show({
		        animation: 'fade-in',
		      });
		    },

		    hide: function() {
		      $ionicLoading.hide();	
		    }
		 };
	} ]);
})();