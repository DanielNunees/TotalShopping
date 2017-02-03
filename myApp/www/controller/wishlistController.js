(function() {
    'use strict';
angular.module('app')
.controller('wishlistController', ['$scope','$auth','$ionicNavBarDelegate','wishlistFactory','cacheFactory','alertsFactory', function($scope,$auth,$ionicNavBarDelegate,wishlistFactory,cacheFactory,alertsFactory){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicNavBarDelegate.showBackButton(true);
    getWishlist();
  });

  $scope.isAuthenticated = $auth.isAuthenticated();

  var getWishlist = function(){
    var cache = cacheFactory.info();
    if(cache.size>0){
      getWishlistFromCache(cache);
      return;
    }
      
    wishlistFactory.getWishlist().then(function successCallback(data){
      $scope.products = [];
      var i=0;
      if(data.length>0 && !angular.isUndefined(data[0]['id_product']))
      angular.forEach(data, function(value1, key1) {
          $scope.products.push({productPrice:value1['product']['description'][0]['product_price']['price'],
                          productName:value1['product']['description'][0]['name'],
                          productImages: value1['product']['images'],
                          productId: value1['id_product']
                          });

          cacheFactory.put(value1['id_product'],$scope.products[i]);
          wishlistFactory.setKeys(value1['id_product']);
          i++;

      });
      },function errorCallback(response) {
        /* Tratamento de erros*/
        console.log(response);
        if(response.status == 404){
          alertsFactory.showAlert('Error 404','Sua lista está vazia');
        }else if(response.status == 400){
          alertsFactory.showAlert('Error 400','Lista não criada');
      }
      /* Fim Tratamento de erros*/
    });
  }

  var getWishlistFromCache = function(cache){
    console.log("from cache")
      var keys = wishlistFactory.getKeys();
        $scope.products = [];
        angular.forEach(keys, function(value,key){
          var product = cacheFactory.get(value);
          $scope.products.push({productPrice:product['productPrice'],
                          productName:product['productName'],
                          productImages: product['productImages'],
                          productId: product['productId']
                          });
        });
  }



  $scope.remove = function(index,id_product){
    wishlistFactory.removeProduct(id_product).then(function successCallback(response){
      console.log(response);
      $scope.products.splice(index,1);
    },function errorCallback(response) {
      console.log(response);
    });
  }
    
  }])
})();

