(function() {
    'use strict';
	angular.module('app').
	factory('transactionFactory', ['$http','valueConfig','$httpParamSerializerJQLike', function($http,valueConfig,$httpParamSerializerJQLike) {
		return {
			getTransactionStatus: function(reference){
				return $http({
					method: 'POST',
					url: valueConfig.baseUrl+'/searchTransacion/byReference/status',
					dataType: 'json',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			        data: $httpParamSerializerJQLike({'reference':reference})
				});
			},

			getTransactionType: function(reference){
				return $http({
					method: 'POST',
					url: valueConfig.baseUrl+'/searchTransacion/byReference/type',
					dataType: 'json',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			        data: $httpParamSerializerJQLike({'reference':reference})
				});
			}
		}
  	}]);
})();