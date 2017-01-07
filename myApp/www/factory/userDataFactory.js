(function() {
    'use strict';
angular.module('app')
.factory('userDataFactory', function($http,$httpParamSerializerJQLike,$q){
	var userData;
	return{

	 	loadUserData: function(){
			return userData? $q.when(userData) : $http({
		        method: 'GET',
		        url: 'http://127.0.1.1/laravel/public/user/loadData',
		        cache: true,
		    }).then(function(result){

            // What we return here is the data that will be accessible 
            // to us after the promise resolves
            userData = result.data;
            return userData;
        });
		},

		updateAddress: function(address){
			return $http({
		        method: 'POST',
		        url: 'http://127.0.1.1/laravel/public/user/updateAddress',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike(address)
		    });
		},

		createAddress: function(address){
			$http({
		        method: 'POST',
		        url: 'http://127.0.1.1/laravel/public/user/createAddress',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike(address) 
		    });
		},
		
		resetUserData: function(){
			userData = undefined;
		},

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