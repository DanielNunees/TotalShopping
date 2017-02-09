(function() {
    'use strict';
	angular.module('app')
	.factory('checkoutFactory', function($http,$httpParamSerializerJQLike,$q,valueConfig){
		var session_ID;
		return {

			getSession: function(){
				return session_ID? $q.when(session_ID) : $http({
			        method: 'GET',
			        url: valueConfig.baseUrl+'/getSession',
			        cache: true,
			    }).then(function(response){

			    	PagSeguroDirectPayment.setSessionId(response.data);
			    	session_ID = response.data;
			    	return session_ID;
			    }); 
			},

			creditCardCheckout: function(checkoutData){
				return $http({
			        method: 'POST',
			        url: valueConfig.baseUrl+'/creditCardCheckout',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=ISO-8859-1'},
			        data: $httpParamSerializerJQLike(checkoutData)
			    })
			},

			boletoCheckout: function(checkoutData){
				return $http({
			        method: 'POST',
			        url: valueConfig.baseUrl+'/boletoCheckout',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=ISO-8859-1'},
			        data: $httpParamSerializerJQLike({'checkoutData':checkoutData})
			    })
			},
			resetSessionId: function(){
				session_ID = undefined;
				console.log('resetSessionId');
			}

		}
	});
})();