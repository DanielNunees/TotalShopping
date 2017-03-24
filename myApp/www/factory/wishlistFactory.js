(function() {
    'use strict';
	angular.module('app')
	.factory('wishlistFactory', function($http,$httpParamSerializerJQLike,ngCart,valueConfig,wishlistCacheFactory){
		var _keys = [];
		var products = [];
		return {
			getWishlist: function(){
				return $http({
					method: 'GET',
					url: valueConfig.baseUrl+'/wishlist'
				})
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
			    		var i = _keys.indexOf(id_product);
						if(i === -1) {
							_keys.push(id_product);
			    			wishlistCacheFactory.put(id_product,product);
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
	      			wishlistCacheFactory.remove(id_product);
	      			var i = _keys.indexOf(id_product);
					if(i != -1) {
						_keys.splice(i, 1);
					}
	      			return (response.data);
			    }); 
			},

			getKeys: function(){
				return _keys;
			},

			setKeys: function(id_product){
				return _keys.push(id_product);
			},

			removeAll: function(){
				_keys.length = 0;
				wishlistCacheFactory.removeAll();
			}
		}
	});
})();