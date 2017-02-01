(function() {
    'use strict';
	angular.module('app')
	.factory('wishlistFactory', function($http,$httpParamSerializerJQLike,ngCart,valueConfig,cacheFactory){
		var keys = [];
		var products = [];
		return {
			getWishlist: function(){
				return $http({
					method: 'GET',
					url: valueConfig.baseUrl+'/wishlist'
				}).then(function(response){
					return response.data;
				});
			},

			favoriteProduct: function(id_product,product_attribute,product){
				return $http({
					method: "POST",
					url: valueConfig.baseUrl+'/wishlistAddProduct',
					dataType: 'json',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			        data: $httpParamSerializerJQLike({'id_product':id_product,
                                          			  'id_product_attribute':product_attribute})
			    	}).then(function(response){
			    		var i = keys.indexOf(id_product);
						if(i === -1) {
							keys.push(id_product);
			    			cacheFactory.put(id_product,product);
						}
			        	return response;
                    });
                    
			},

			removeProduct: function(id_product){
				return $http({
			          method: 'POST',
			          url: valueConfig.baseUrl+'/removeWishlistProduct',
			          dataType: 'json',
			          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			          data: $httpParamSerializerJQLike({'id_product':id_product})
	      		}).then(function(response){
	      			cacheFactory.remove(id_product);
	      			var i = keys.indexOf(id_product);
					if(i != -1) {
						keys.splice(i, 1);
					}
	      			return (response.data);
			    }); 
			},

			getKeys: function(){
				return keys;
			},

			setKeys: function(id_product){
				return keys.push(id_product);
			}
		}
	});
})();