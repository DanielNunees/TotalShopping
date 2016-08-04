app.controller('userDataCheckoutController', ['$scope','$ionicNavBarDelegate','$http','$httpParamSerializerJQLike','$auth','userDataFactory','$q',  function($scope,$ionicNavBarDelegate,$http,$httpParamSerializerJQLike,$auth,userDataFactory,$q){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
    });
    $scope.userData = {};

 	$scope.isAuthenticated = function() {
	  return $auth.isAuthenticated();
	};

	userDataFactory.loadUserData().then(function successCallback(response) {
      	$scope.isEmpty = false;
      	$scope.userData = response.data.address[0];
      	$scope.userBirth = response.data.user[0];
      	angular.forEach($scope.states2,function(value,key){
      		if(response.data.address[0].id_state==value.value){
      			$scope.userData.state = value.state;
      		}
      	});
        }, function errorCallback(response) {
        	/* Tratamento de erros*/
	      	//error 400 - No content
	      	if(response.status==400){
	      		$scope.isEmpty = true;
	      	}
	      	else{$scope.isEmpty=false;}
	      	/* Fim Tratamento de erros*/
         	console.log(response);
        });

	userDataFactory.updateAddress().then(function successCallback(response) {
      	console.log(response.data);
      	$scope.userData = response.data[0];
      	angular.forEach($scope.states2,function(value,key){
      		if(response.data[0].id_state==value.value){
      			$scope.userData.state = value.state;
      		}
      	});

        }, function errorCallback(response) {
	       	/* Tratamento de erros*/
	      	//error 400 - No content
	      	if(response.status==400){
	      		$scope.isEmpty = true;
	      	}
	      	else{$scope.isEmpty=false;}
	      	/* Fim Tratamento de erros*/
	         	console.log(response.data);
        });

  		


}]);