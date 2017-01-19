(function() {
    'use strict';
	angular.module('app')
	.factory('wishlistFactory', function($http,$httpParamSerializerJQLike,ngCart){
		var id_product;
		return {
			getWishlist: function(){
				return $http({
					method: 'GET',
					url: 'http://127.0.1.1/laravel/public/wishlist'
				}).then(function(response){
					return response.data;
				});
			},

			removeProduct: function(id_product){
				return $http({
			          method: 'POST',
			          url: 'http://127.0.1.1/laravel/public/removeWishlistProduct',
			          dataType: 'json',
			          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			          data: $httpParamSerializerJQLike({'id_product':id_product})
	      		}).then(function(response){
	      			return (response.data);
			    }); 
			}
		}
	});
})();