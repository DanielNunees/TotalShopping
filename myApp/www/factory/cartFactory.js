(function() {
    'use strict';
	angular.module('app')
	.factory('cartFactory', function($http,$httpParamSerializerJQLike){
		return {
			removeProduct: function(id_product,id_product_attribute){
				return $http({
			        method: 'POST',
			        url: 'http://127.0.1.1/laravel/public/cartRemoveProducts',
			        dataType: 'json',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			        data: $httpParamSerializerJQLike({'id_product':id_product,
	                                          	      'id_product_attribute':id_product_attribute})
	      		}).then(function(response){
	      			console.log(response);
			    }); 
			},
			loadCart: function(){
				return $http({
			        method: 'POST',
			        url: 'http://127.0.1.1/laravel/public/cartLoad',
	      		}).then(function(response){
	      			return response.data;
			    }); 
			}
		}
	});
})();