app.controller('userDashboardController', ['$scope','$auth','$location','$ionicHistory','$ionicSlideBoxDelegate','$http','$httpParamSerializerJQLike','$ionicModal','$ionicNavBarDelegate','userDataFactory', function($scope,$auth,$location,$ionicHistory,$ionicSlideBoxDelegate,$http,$httpParamSerializerJQLike,$ionicModal,$ionicNavBarDelegate,userDataFactory){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
		$ionicNavBarDelegate.showBackButton(true);
		$ionicSlideBoxDelegate.slide(0, [0]);
    	$scope.loadData();
    	$scope.slide = 0;
  	});

	$scope.address = {};
	$scope.states2 = [{state:'Acre',value:'313'},{state:'Alagoas',value:'314'},{state:'Amapá',value:'315'},{state:'Amazonas',value:'316'},{state:'Bahia',value:'317'},
					  {state:'Ceará',value:'318'},{state:'Distrito Federal',value:'319'},{state:'Espírito Santo',value:'320'},{state:'Goiás',value:'321'},{state:'Maranhão',value:'322'},
					  {state:'Mato Grosso',value:'323'},{state:'Mato Grosso do Sul',value:'324'},{state:'Minas Gerais',value:'325'},{state:'Pará',value:'326'},{state:'Paraíba',value:'327'},
					  {state:'Paraná',value:'328'},{state:'Pernanbuco',value:'329'},{state:'Piauí',value:'330'},{state:'Rio de Janeiro',value:'331'},{state:'Rio Grande do Norte',value:'332'},
					  {state:'Rio Grande do Sul',value:'333'},{state:'Rondônia',value:'334'},{state:'Roraima',value:'335'},{state:'Santa Catarina',value:'336'},{state:'São Paulo',value:'337'},
					  {state:'Sergipe',value:'338'},{state:'Tocantins',value:'339'}]

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
         	console.log(response.data);
        });
	}

	$scope.createAddress = function(){
		$scope.modal.hide();
		$scope.address.id_customer = localStorage.id;
		$http({
        method: 'POST',
        url: 'http://127.0.1.1/laravel/public/user/createAddress',
        dataType: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: $httpParamSerializerJQLike($scope.address)
        
      }).then(function successCallback(response) {
      		$scope.address = {};
      		console.log(response.data);
        }, function errorCallback(response) {
         	console.log(response);
        });
	}

	$scope.updateAddress = function(){
		
		$scope.address.id_customer = localStorage.id;
		$http({
        method: 'POST',
        url: 'http://127.0.1.1/laravel/public/user/updateAddress',
        dataType: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: $httpParamSerializerJQLike($scope.address)
        
      }).then(function successCallback(response) {
      	$scope.modal.hide();
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