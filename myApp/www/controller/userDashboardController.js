app.controller('userDashboardController', ['$scope','$auth','$location','$ionicHistory','$ionicSlideBoxDelegate','$http','$httpParamSerializerJQLike','$ionicModal','$ionicNavBarDelegate','userDataFactory', function($scope,$auth,$location,$ionicHistory,$ionicSlideBoxDelegate,$http,$httpParamSerializerJQLike,$ionicModal,$ionicNavBarDelegate,userDataFactory){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
		$ionicNavBarDelegate.showBackButton(true);
		$ionicSlideBoxDelegate.slide(0, [0]);
    	$scope.loadData();
    	$scope.slide = 0;
  	});

	$scope.address = {};

	$scope.isAuthenticated = function() {
	  return $auth.isAuthenticated();
	};

	$scope.logout = function(){
		$auth.logout();
		$location.url('/user/home');
		//$ionicHistory.removeBackView();
	}

	$scope.slideChanged = function(index) {
		$ionicSlideBoxDelegate.slide(index, [300]);
	};

	$scope.slideHasChanged = function(index){
		$scope.slide = $ionicSlideBoxDelegate.currentIndex();
	}

	$scope.loadData = function(){
		userDataFactory.loadUserData().then(function successCallback(response) {
		console.log(response.data.user[0]);
      	$scope.isEmpty = false;
      	$scope.userData = response.data.address[0];
      	$scope.userBirth = response.data.user[0];

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
	}

	$scope.createAddress = function(){
		$scope.address.id_customer = localStorage.id;
		$scope.address.address1 =$scope.address.address1+','+$scope.address.number;
		userDataFactory.createAddress($scope.address).then(function successCallback(response) {
	      		$scope.modal.hide();
	      		$scope.address = {};
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
	}

	$scope.updateAddress = function(){
		$scope.address.id_customer = localStorage.id;
		$scope.address.address1 =$scope.address.address1+','+$scope.address.number;
		userDataFactory.updateAddress($scope.address).then(function successCallback(response) {
	      	$scope.modal.hide();
	      	$scope.address = {};
	      	$scope.userData = response.data[0];

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
	}
	$ionicModal.fromTemplateUrl('view/userAddressRegisterModal.html', {
      scope: $scope,
      animation: 'slide-in-up',

	  }).then(function(modal) {
	    $scope.modal = modal;
	  });

	  $scope.openModal = function() {
	    $scope.modal.show();
	  };

	  $scope.closeModal = function() {
	    $scope.modal.hide();
	    $scope.loadData();
	  };
	  // Cleanup the modal when we're done with it!
	  $scope.$on('$destroy', function() {
	    $scope.modal.remove();
	  });
	  // Execute action on hide modal
	  $scope.$on('modal.hidden', function() {
	    // Execute action
	    console.log('hidden');
	    $scope.loadData();
	    $scope.address = {};
	  });
	  // Execute action on remove modal
	  $scope.$on('modal.removed', function() {
	    // Execute action
	    $scope.loadData();
	  });

}]);