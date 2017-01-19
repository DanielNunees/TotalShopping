(function() {
    'use strict';
	angular.module('app')
	.factory('cartFactory', function($http,$httpParamSerializerJQLike,ngCart){
		var id_product;
		return {
			addProduct: function(id_product,id_product_attribute,quantity){
				return $http({
					method: 'POST',
					url: 'http://127.0.1.1/laravel/public/cartAddProducts',
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
				id_product = id_product;
				return $http({
			        method: 'POST',
			        url: 'http://127.0.1.1/laravel/public/cartRemoveProducts',
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
			        url: 'http://127.0.1.1/laravel/public/cartLoad',
	      		}).then(function(response){
	      			return response.data;
			    }); 
			}
		}
	});
})();