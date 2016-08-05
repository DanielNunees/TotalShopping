
app.controller('productController', ['$scope', '$http','$stateParams', function($scope,$http,$stateParams){

  
  $scope.loadProducts = function(){
    $http.get('http://127.0.1.1/laravel/public/product/'+$stateParams.productId).then(   
      function(data){
        $scope.product = data.data;
        produto = data.data;
        var size = [];
        angular.forEach(produto.attributes,function(value,key){
          if(angular.isNumber(key)){
            size.push(value);
          }else{
            angular.forEach(value,function(value1,key){
              size.push(value1);
            })
          }
        })
        $scope.sizes = size;
      },
      function(err){
      }
    )
  }
    $scope.loadProducts();

}]);

