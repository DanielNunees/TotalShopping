(function() {
    'use strict';
angular.module('app')
.factory('userFactory', function($http,$httpParamSerializerJQLike,valueConfig){
	return{

	 	loadUserData: function(){
			return $http({
		        method: 'GET',
		        cache: true,
		        url: valueConfig.baseUrl+'/user/loadData',
		    });
		},

		registerUser: function(user){
			return $http({
				method: 'POST',
			    url: valueConfig.baseUrl+'/user/register',
			    dataType: 'json',
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			    data: $httpParamSerializerJQLike(user)
			})
		},
		
		updateOrCreateAddress: function(address){
			return $http({
		        method: 'POST',
		        url: valueConfig.baseUrl+'/user/updateOrCreateAddress',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike(address)
		    });
		}
	}

});
})();