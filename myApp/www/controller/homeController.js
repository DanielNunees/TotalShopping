app.controller('homeController', ['$scope', '$http','$ionicHistory','$ionicNavBarDelegate', function($scope,$http,$ionicHistory,$ionicNavBarDelegate){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    
    $ionicHistory.clearCache();
    $ionicHistory.clearHistory();
    $ionicNavBarDelegate.showBackButton(false);
    //console.log($ionicHistory.viewHistory());
  });

  $scope.loadProducts= function(){
    $http.get('http://127.0.1.1/laravel/public/home',{ cache: true}).then(
      
      function(data){
        $scope.product = data.data;       
      },
      function(err){
        //alert('deu ruim la no servidor');
      }
    )

  }
    $scope.loadProducts();


}]);