
app.controller('productController', ['$scope', '$http','$stateParams','$location','$ionicHistory','$httpParamSerializerJQLike','$ionicPopup','$auth', function($scope,$http,$stateParams,$location,$ionicHistory,$httpParamSerializerJQLike,$ionicPopup,$auth){
  $scope.product = {};
  $scope.added = false;
  
  $scope.isAuthenticated = function() {
    return $auth.isAuthenticated();
  };

  $scope.loadProducts = function(){
    $http.get('http://127.0.1.1/laravel/public/product/'+$stateParams.productId,{ cache: true}).then(   
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
        $location.path('/home');
      }
    )
  }

  

  $scope.favoriteProduct = function(){
    if($scope.isAuthenticated()){
      $http({
        method: 'POST',
        url: 'http://127.0.1.1/laravel/public/createWishlist',
        dataType: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: $httpParamSerializerJQLike({'id_customer':localStorage.id,'id_product':$scope.product.description[0].id_product,
                                          'id_product_attribute':$scope.product.description[0].product_stock_complete[0].id_product_attribute})
        
      }).then(function successCallback(response) {
        $scope.added = true;
        localStorage.setItem('product',[10,20,30]);
        console.log(response.data);
        var alertPopup = $ionicPopup.alert({
          template: 'Adicionado aos favoritos!'
        });
        

        }, function errorCallback(response) {
          var alertPopup = $ionicPopup.alert({
            template: 'Algo de errado aconteceu!'
          });
          
        });
    }else{console.log('Nao disponivel');}
  }
    
  $scope.loadProducts();
}]);

