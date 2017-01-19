(function() {
    'use strict';
	angular.module('app')
	.factory('productFactory', function($http,$httpParamSerializerJQLike){
		return {

			getAllProducts: function(){
				return $http({
					method: 'GET',
					url: 'http://127.0.1.1/laravel/public/home'
				}).then(function successCallback(response){
					return response.data;
				})
			},

			getProduct: function(id_product){
				return $http({
			        method: 'GET',
			        url: 'http://127.0.1.1/laravel/public/product/'+id_product
			        }).then(function(response){
	      			return(response.data);
			    }); 
			},

			favoriteProduct: function(id_product,product_attribute){
				return $http({
					method: "POST",
					url: 'http://127.0.1.1/laravel/public/createWishlist',
					dataType: 'json',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			        data: $httpParamSerializerJQLike({'id_product':id_product,
                                          			  'id_product_attribute':product_attribute})
			    	}).then(function(response){
			        	return response
                    });
			}
		}
	});
})();