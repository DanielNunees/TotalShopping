app.controller('userLoginController', ['$scope', '$http','$auth','$location','$ionicHistory','$ionicPopup','$ionicNavBarDelegate', function($scope,$http,$auth,$location,$ionicHistory,$ionicPopup,$ionicNavBarDelegate){
    $scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
    });
    $scope.loginData = {};

    $scope.isAuthenticated = function() {
      return $auth.isAuthenticated();
    };


    $scope.login = function(){

      $auth.login($scope.loginData).then(
        function(response){
          $location.url('/user/dashboard');
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
        }
      )
    }


}]);

