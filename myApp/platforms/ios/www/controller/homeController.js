
app.controller('homeController', ['$scope', '$http', function($scope,$http){
  

  $scope.loadProducts= function(){
    $http.get('http://127.0.1.1/laravel/public/home').then(
      
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