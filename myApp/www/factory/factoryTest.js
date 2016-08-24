app.factory('factoryTest', function($http,$httpParamSerializerJQLike,$q){

	var userData;
	return{

	 	loadUserData: function(){
			return userData? $q.when(userData) : $http({
		        method: 'POST',
		        url: 'http://127.0.1.1/laravel/public/user/loadData',
		        dataType: 'json',
		        cache: true,
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike({'id_customer':localStorage.id,'key_id_customer_retrieving':'DHC7BB2K3FGJPHQ87VFJ7MDJD'})
		        
		    }).then(function(result){

            // What we return here is the data that will be accessible 
            // to us after the promise resolves
            userData = result.data;
            return userData;
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
		},

		createAddress: function(address){
			$http({
		        method: 'POST',
		        url: 'http://127.0.1.1/laravel/public/user/createAddress',
		        dataType: 'json',
		        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		        data: $httpParamSerializerJQLike(address) 
		    });
		},
		
		resetUserData: function(){
			userData = undefined;
		}
	}

});