(function() {
    'use strict';
angular.module('app')
.factory('userFactory', function($http,$httpParamSerializerJQLike,valueConfig){
	return{

	 	loadUserData: function(){
			return $http({
		        method: 'GET',
		        url: valueConfig.baseUrl+'/user/loadData',
		    }).then(function(result){

            // What we return here is the data that will be accessible 
            // to us after the promise resolves
            return result.data;
        	});
		},

		registerUser: function(user){
			return $http({
				method: 'POST',
			    url: valueConfig.baseUrl+'/user/register',
			    dataType: 'json',
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			    data: $httpParamSerializerJQLike(user)
			}).then(function successCallBack(response){
				return response.data;
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