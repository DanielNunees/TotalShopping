(function() {
    'use strict';
	angular.module('app').
	factory('dashboardErrorFactory', function($ionicPopup) {
    	return {
			showAlert: function(title,text) {
		    	var alertPopup = $ionicPopup.alert({
		       	title: title,
		       	template: text
			    });
		    }h
		}
  	});
})();