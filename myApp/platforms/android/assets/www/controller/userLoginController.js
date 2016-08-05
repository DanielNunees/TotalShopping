
app.controller('userLoginController', ['$scope', '$http','$auth', function($scope,$http,$auth){
    $scope.loginData = {};

    $scope.login = function(){
      //console.log($scope.loginData);
      $auth.login($scope.loginData).then(
        function(response){
          console.log(response);
          console.log('bem vindo')
        },
        function(error){
          console.log(error);
        }



      )
    }


}]);

