(function() {
    'use strict';
	angular.module('app')
	.controller('userController', ['$scope','$ionicNavBarDelegate','$auth','$state','$ionicHistory','$ionicModal','alertsFactory','$ionicLoading', function($scope,$ionicNavBarDelegate,$auth,$state,$ionicHistory,$ionicModal,alertsFactory,$ionicLoading){

		$scope.$on("$ionicView.beforeEnter", function(event, data){
	      $ionicNavBarDelegate.showBackButton(true);
	      if($auth.isAuthenticated()){
			$state.go('userDashboard');
		}
	    });

	    
		$scope.isAuthenticated = function() {
      		return $auth.isAuthenticated();
    	};

    	$scope.isAuth = $auth.isAuthenticated();
		$scope.login = function(loginData){
	      $auth.login(loginData).then(
	        function(response){
	          $state.go('userDashboard');
	          delete $scope.loginData;
	        },
	        function(error){
	          console.log(error);
	          $scope.loginData = {};
	          alertsFactory.showAlert("Erro de Autenticação","Seu email ou senha estão errados, confira seus dados.Se caso ainda não"
	          	+"for registrado clique no botão Registrar Agora");
	           delete $scope.loginData;
	        });
	    } 

	    $scope.signup = function(user){
	    	$auth.signup(user)
			  .then(function(response) {
			    // Redirect user here to login page or perhaps some other intermediate page
			    // that requires email address verification before any other part of the site
			    // can be accessed.
			    $state.go('userLogin');
	          	$ionicHistory.removeBackView();
			  })
			  .catch(function(response) {
			    // Handle errors here.
			    console.log(response);
			});
	    }

		$ionicModal.fromTemplateUrl('view/userRegister.html', {
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
	      console.log('close');
	    };
	    
	    // Cleanup the modal when we're done with it!
	    $scope.$on('$destroy', function() {
	      $scope.modal.remove();
	    });
	    
	    // Execute action on hide modal
	    $scope.$on('modal.hidden', function() {
	      // Execute action
	      console.log('hidden');
	    });
	    
	    // Execute action on remove modal
	    $scope.$on('modal.removed', function() {
	      // Execute action
	      
	    });

	}]);
})();