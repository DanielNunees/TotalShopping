(function() {
    'use strict';
	angular.module('app')
	.factory('wishlistFactory', function($http,$httpParamSerializerJQLike,ngCart,valueConfig){
		return {
			getWishlist: function(){
				return $http({
					method: 'GET',
					url: valueConfig.baseUrl+'/wishlist'
				}).then(function(response){
					return response.data;
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
	      			return (response.data);
			    }); 
			}
		}
	});
})();