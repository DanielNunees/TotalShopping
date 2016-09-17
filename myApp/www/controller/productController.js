(function() {
  'use strict';
angular.module('app')
.controller('productController', ['$scope', '$http','$stateParams','$location','$ionicHistory','$httpParamSerializerJQLike','$ionicPopup','$auth','$ionicNavBarDelegate','ngCart','ngCartItem', function($scope,$http,$stateParams,$location,$ionicHistory,$httpParamSerializerJQLike,$ionicPopup,$auth,$ionicNavBarDelegate,ngCart,ngCartItem){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicNavBarDelegate.showBackButton(true);
  });

  $scope.product = {};
  $scope.added = false;
  var guest;
  
  $scope.isAuthenticated = function() {
    return $auth.isAuthenticated();
  };

  $scope.loadProducts = function(){
    $http.get('http://127.0.1.1/laravel/public/product/'+$stateParams.productId,{ cache: true}).then(   
      function(data){
        console.log(data.data);
        $scope.product = data.data;
        $scope.productPrice = data.data['description'][0]['product_price']['price'];
        $scope.productName = data.data['description'][0]['name'];
        $scope.productId = data.data['description'][0]['id_product'];
        $scope.productDescription = data.data['description'][0]['description'];
        $scope.productImages = [];
        angular.forEach(data.data['images'],function(value,key){
          $scope.productImages.push(value.image);
        })
        $scope.productAttributtes = data.data['attributes'];
        
        $scope.sizes = []
        if(data.data['attributes'].length>1){
          angular.forEach(data.data['attributes'],function(value1,key1){
            angular.forEach(value1,function(value2,key2){
              console.log(key2);
              $scope.sizes.push(value2);
            })
          })

          console.log($scope.sizes);
        }
        else{
          angular.forEach(data.data['attributes'],function(value,key){
            if(angular.isNumber(key)){
              $scope.sizes.push(value);
            }else{
              angular.forEach(value,function(value1,key){
                $scope.sizes.push(value1);
              })
            }
          })
          $scope.sizes.sort();
          //$scope.firstSize = $scope.sizes[0];
        }




        
        
      },
      function(err){
        $location.path('/home');
      }
    )
  }

  function isGuest(){
    var id_customer = localStorage.id;
    if(!$auth.isAuthenticated()){
      guest = null;
      console.log('guest = null');
    }
    else{
      guest = !null; //true
      console.log('guest = true');
    }
  }
  

  $scope.teste = function(id_product){
    var item = ngCart.getItemById(String(id_product));
    var quantity = item.getQuantity();
    isGuest();
    $http({
        method: 'POST',
        url: 'http://127.0.1.1/laravel/public/cartAddProducts',
        dataType: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: $httpParamSerializerJQLike({'id_customer':localStorage.id,'isGuest':guest,'id_product':$scope.product.description[0].id_product,'product_quantity':quantity,
                                          'id_product_attribute':$scope.product.description[0].product_stock_complete[0].id_product_attribute})
        
      }).then(function successCallback(response) {
          console.log(response);
          if(guest==null){
            localStorage.id = response.data[0]['id_guest'];
            guest=!null;
          }
          else{
            console.log(response.data);
          }


        },function errorCallback(response) {

        });
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
        //localStorage.setItem('product',[10,20,30]);
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