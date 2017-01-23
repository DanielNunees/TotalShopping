(function() {
    'use strict';
angular.module('app')
.factory('dashboardFactory', function($http){
	var userData;
	return{

		loadHistoric: function(){
			return $http({
				method: 'GET',
				url: 'http://127.0.1.1/laravel/public/historic/getHistoric'
			}).then(function(data){
				return data.data;
			})
		}

	}

});
})();