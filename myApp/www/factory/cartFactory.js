(function() {
    'use strict';
	angular.module('app')
	.factory('cartFactory', function($http,$httpParamSerializerJQLike,ngCart,valueConfig){
		return {
			addProduct: function(id_product,id_product_attribute,quantity){
				return $http({
					method: 'POST',
					url: valueConfig.baseUrl+'/cartAddProducts',
					dataType: 'json',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			        data: $httpParamSerializerJQLike({'id_product':id_product,
	                                          	      'id_product_attribute':id_product_attribute,
	                                          	  		'product_quantity':quantity})
				}).then(function(response){
					return response;
				});
			},

			removeProduct: function(id_product,id_product_attribute){
				console.log(id_product,id_product_attribute);
				return $http({
			        method: 'POST',
			        url: valueConfig.baseUrl+'/cartRemoveProducts',
			        dataType: 'json',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			        data: $httpParamSerializerJQLike({'id_product':id_product,
	                                          	      'id_product_attribute':id_product_attribute})
	      		}).then(function(response){
	      			ngCart.removeItemById(  parseInt(id_product));
	      			console.log(response.data);
			    }); 
			},

			loadCart: function(){
				return $http({
			        method: 'GET',
			        url: valueConfig.baseUrl+'/cartLoad',
	      		}).then(function(response){
	      			return response.data;
			    }); 
			}
		}
	});
})();