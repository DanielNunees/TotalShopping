(function() {
  'use strict';
angular.module('app')
.controller('productController', ['$scope', '$http','$stateParams','$location','$ionicHistory','$httpParamSerializerJQLike','$ionicPopup','$auth','$ionicNavBarDelegate', function($scope,$http,$stateParams,$location,$ionicHistory,$httpParamSerializerJQLike,$ionicPopup,$auth,$ionicNavBarDelegate){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicNavBarDelegate.showBackButton(true);
  });

  $scope.product = {};
  $scope.added = false;
  
  $scope.isAuthenticated = function() {
    return $auth.isAuthenticated();
  };

  $scope.loadProducts = function(){
    $http.get('http://127.0.1.1/laravel/public/product/'+$stateParams.productId,{ cache: true}).then(   
      function(data){
        $scope.product = data.data;
        var produto = data.data;
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
        size.sort();
        $scope.sizes = size;
        $scope.firstSize = size[0];
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
        

        },function errorCallback(response) {
          console.log(response.data);
          if(response.status == 400){
              var alertPopup = $ionicPopup.alert({
              title: 'Error 400',
              template: 'Whislist not created',
            });
          }
          
        });
    }else{var alertPopup = $ionicPopup.alert({
          template: 'Faça Login Primeiro'
        })}
  }
    
  $scope.loadProducts();
}]);

})();