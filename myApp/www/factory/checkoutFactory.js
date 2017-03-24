(function() {
    'use strict';
	angular.module('app')
	.factory('checkoutFactory', function($http,$httpParamSerializerJQLike,$q,valueConfig){
		var _session_ID;
		return {

			getSession: function(){
				return _session_ID? $q.when(_session_ID) : $http({
			        method: 'GET',
			        url: valueConfig.baseUrl+'/getSession',
			        cache: true,
			    });
			},

			setSession: function(sessionID){
				_session_ID = sessionID;
			},

			creditCardCheckout: function(checkoutData){
				console.log(checkoutData);
				return $http({
			        method: 'POST',
			        url: valueConfig.baseUrl+'/creditCardCheckout',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=ISO-8859-1'},
			        data: $httpParamSerializerJQLike({'checkoutData':checkoutData})
			    });
			},

			boletoCheckout: function(checkoutData){
				return $http({
			        method: 'POST',
			        url: valueConfig.baseUrl+'/boletoCheckout',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=ISO-8859-1'},
			        data: $httpParamSerializerJQLike({'checkoutData':checkoutData})
			    });
			},
			resetSessionId: function(){
				_session_ID = undefined;
				console.log('resetSessionId');
			}

		}
	});
})();