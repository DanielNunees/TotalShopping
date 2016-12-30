(function() {
    'use strict';
angular.module('app')
.controller('userLoginController', ['$scope', '$http','$auth','$ionicHistory','$ionicPopup','$ionicNavBarDelegate','$ionicModal','$httpParamSerializerJQLike','$state', function($scope,$http,$auth,$ionicHistory,$ionicPopup,$ionicNavBarDelegate,$ionicModal,$httpParamSerializerJQLike,$state){
    $scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
    });
    
    $scope.loginData = {};
    $scope.userRegister = {};

    $scope.authenticate = function(provider) {
      $auth.authenticate(provider);
    };
   
    $scope.isAuthenticated = function() {
      return $auth.isAuthenticated();
    };


    $scope.login = function(){

      $auth.login($scope.loginData).then(
        function(response){
          $state.go('userDashboard');
          $scope.loginData = {};
          $ionicHistory.removeBackView();
        },
        function(error){
          console.log(error);
          $scope.loginData = {};
           var alertPopup = $ionicPopup.alert({
             title: 'Failed Authenticantion',
             template: 'Login ou password error, if you are not registered, click in "Register now"'
           });
           $scope.loginData = {};
        }
      )
    }

    $scope.register = function(){
      $http({
      method: 'POST',
      url: 'http://127.0.1.1/laravel/public/user/register',
      dataType: 'json',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      data: $httpParamSerializerJQLike($scope.userRegister)
      
    }).then(function successCallback(response) {
      var alertPopup = $ionicPopup.alert({
              title: 'Usuário Cadastrado',
              template: 'Bem vindo!'
            });
            $scope.modal.hide();
      

      }, function errorCallback(response) {
        var alertPopup = $ionicPopup.alert({
              title: 'Usuário já cadastrado',
              template: 'Seu email já consta em nosso aplicativo, tente recuperar a senha.'
            });
          $scope.userRegister = {};
          $scope.modal.hide();
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
      $scope.loadData();
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
      //$scope.loadData();
    });
    // Execute action on remove modal
    $scope.$on('modal.removed', function() {
      // Execute action
      
    });


}]);

})();