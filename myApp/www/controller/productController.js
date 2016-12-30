(function() {
  'use strict';
angular.module('app')
.controller('productController', ['$scope', '$http','$stateParams','$location','$ionicHistory','$httpParamSerializerJQLike','$ionicPopup','$auth','$ionicNavBarDelegate','ngCart','cartFactory', function($scope,$http,$stateParams,$location,$ionicHistory,$httpParamSerializerJQLike,$ionicPopup,$auth,$ionicNavBarDelegate,ngCart,cartFactory){
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
        $scope.productAttribute = [];
        angular.forEach(data.data['images'],function(value,key){
          $scope.productImages.push(value.image);
        })
        $scope.attributes = data.data['attributes'];

      },
      function(err){
        $location.path('/home');
      }
    )
  }

  function isGuest(){
    if(!$auth.isAuthenticated()){
      guest = null; //false
      console.log('guest = null');
    }
    else{
      guest = !null; //true
      console.log('guest = true');
    }
  }
  

  $scope.addProduct = function(id_product,product_attribute){
    var item = ngCart.getItemById(String(id_product));
    if(item === false || angular.isUndefined(product_attribute)){
      var alertPopup = $ionicPopup.alert({
          template: 'Selecione o Tamanho'
        })
    }
    else{
    
    var quantity = item.getQuantity();
    var product_attributte = item.getData().product_attributte;
    isGuest();
    $http({
        method: 'POST',
        url: 'http://127.0.1.1/laravel/public/cartAddProducts',
        dataType: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: $httpParamSerializerJQLike({'id_customer':localStorage.id,'isGuest':guest,'id_product':$scope.product.description[0].id_product,'product_quantity':quantity,
                                          'id_product_attribute':product_attributte})
        
      }).then(function successCallback(response) {
          console.log(response);
          if(guest==null){
            localStorage.id = response.data[0]['id_guest'];
            guest=!null;
          }
          else{
           // console.log(response.data);
          }


        },function errorCallback(response) {
          console.log(response);
        });
    }
  }

  $scope.favoriteProduct = function(){
    if($scope.isAuthenticated()){
      $http({
        method: 'POST',
        url: 'http://127.0.1.1/laravel/public/createWishlist',
        dataType: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: $httpParamSerializerJQLike({'id_product':$scope.product.description[0].id_product,
                                          'id_product_attribute':$scope.product.description[0].product_stock_complete[0].id_product_attribute})
        
      }).then(function successCallback(response) {
        $scope.added = true;
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
        })
     }
  }
    
  $scope.loadProducts();
}]);

})();