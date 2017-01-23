(function() {
    'use strict';

angular.module('app')
.factory('checkoutFactory', function($http,$httpParamSerializerJQLike,$q){
	var session_ID;
	return {

		getSession: function(){
			return session_ID? $q.when(session_ID) : $http({
		        method: 'GET',
		        url: 'http://127.0.1.1/laravel/public/getSession',
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
		        url: 'http://127.0.1.1/laravel/public/creditCardCheckout',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike({'checkoutData':checkoutData})
		    })
		},

		boletoCheckout: function(checkoutData){
			return $http({
		        method: 'POST',
		        url: 'http://127.0.1.1/laravel/public/boletoCheckout',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
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