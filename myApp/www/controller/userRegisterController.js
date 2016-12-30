
(function() {
    'use strict';
    angular.module('app')
.controller('userRegisterController', ['$scope','$http','$httpParamSerializerJQLike','$ionicPopup','$auth', function($scope,$http,$httpParamSerializerJQLike,$ionicPopup,$auth){
	$scope.userRegister={};
	

      $scope.submit = function(){
      	//console.log($scope.userRegister);
      	$http({
		  method: 'POST',
		  url: 'http://127.0.1.1/laravel/public/user/register',
		  dataType: 'json',
		  headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		  data: $httpParamSerializerJQLike({'userRegister':$scope.userRegister,'token':$auth.getToken()})
		  
		}).then(function successCallback(response) {
			$scope.userRegister={};
			var alertPopup = $ionicPopup.alert({
            	title: 'Usuário Cadastrado',
            	template: 'Bem vindo!'
           	});
			

		  }, function errorCallback(response) {
		  	$scope.userRegister={};
		  	var alertPopup = $ionicPopup.alert({
            	title: 'Usuário já cadastrado',
            	template: 'Seu email já consta em nosso aplicativo, tente recuperar a senha.'
           	});
		  });
      	}


	document.getElementById("files").onchange = function () {
	    var reader = new FileReader();

	    reader.onload = function (e) {
	        // get loaded data and render thumbnail.
	        document.getElementById("image").src = e.target.result;
	    };

	    // read the image file as a data URL.
	    reader.readAsDataURL(this.files[0]);
	};
}]);

/*
app.controller('userRegisterController', ['$scope','$http','$httpParamSerializerJQLike','$auth',  function($auth,$scope,$http,$httpParamSerializerJQLike){
		$scope.userRegister={};
		var options = {
			  method: 'POST',
			  url: 'http://127.0.1.1/laravel/public/user/register',
			  dataType: 'json',
			  headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			};
	
		$scope.signup = function(){
			console.log('asasdasd');
			$auth.signup($httpParamSerializerJQLike($scope.userRegister),options)
			  .then(function(response) {
			  	console.log(response);
			    // Redirect user here to login page or perhaps some other intermediate page
			    // that requires email address verification before any other part of the site
			    // can be accessed.
			  })
			  .catch(function(response) {
			    // Handle errors here.
			});
		}	

}]);*/
})();