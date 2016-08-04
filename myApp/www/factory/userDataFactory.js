app.factory('userDataFactory', function($http,$httpParamSerializerJQLike){
	return {
	 	loadUserData: function(){
			return $http({
		        method: 'POST',
		        url: 'http://127.0.1.1/laravel/public/user/loadData',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike({'id_customer':localStorage.id,'key_id_customer_retrieving':'DHC7BB2K3FGJPHQ87VFJ7MDJD'})
		        
		    });
		},

		updateAddress: function(address){
			return $http({
		        method: 'POST',
		        url: 'http://127.0.1.1/laravel/public/user/updateAddress',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike(address)
		      	});
		}

	}

});