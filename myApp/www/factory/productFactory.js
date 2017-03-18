(function() {
    'use strict';
	angular.module('app')
	.factory('productFactory', function($http,$httpParamSerializerJQLike,valueConfig){
		var _page = 1;
		return {
			getAllProducts: function(){
				return $http({
					method: 'GET',
					url: valueConfig.baseUrl+'/home/'+_page
				});
			},

			getProduct: function(id_product){
				return $http({
			        method: 'GET',
			        cache: true,
			        url: valueConfig.baseUrl+'/product/'+id_product
			        });
			},

			favoriteProduct: function(id_product,product_attribute){
				return $http({
					method: "POST",
					url: valueConfig.baseUrl+'/createWishlist',
					dataType: 'json',
			        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			        data: $httpParamSerializerJQLike({'id_product':id_product,
                                          			  'id_product_attribute':product_attribute})
			    	});
			},

			getPage: function(){
				return _page;
			},

			nextPage: function(){
				_page++;
			},
			setPage: function(page){
				_page = page;
			}
		}
	});
})();