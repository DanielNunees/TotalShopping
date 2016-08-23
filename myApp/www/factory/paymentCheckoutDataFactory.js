app.factory('paymentCheckout', function($http,$httpParamSerializerJQLike){
	
	return {

		getSession: function(){
			return $http({
		        method: 'POST',
		        url: 'http://127.0.1.1/laravel/public/getSession',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike({})
		    })
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
		}

	}

});