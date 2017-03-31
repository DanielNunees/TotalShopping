(function() {
  'use strict';
angular.module('app')
.controller('productController', ['$scope','$stateParams','$ionicNavBarDelegate','$ionicSlideBoxDelegate','productFactory','cartFactory','ngCart','alertsFactory','wishlistFactory','$auth', function($scope,$stateParams,$ionicNavBarDelegate,$ionicSlideBoxDelegate,productFactory,cartFactory,ngCart,alertsFactory,wishlistFactory,$auth){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicNavBarDelegate.showBackButton(true);
  });

  var getProduct = function(){
    productFactory.getProduct($stateParams.productId).then(function successCallback(data){
      if(!data.data.hasOwnProperty('attributes')){
        $scope.unavailable = true;
      }
      $scope.product = {};
          $scope.product = {productName: data.data['name'],
                            productId: data.data['id_product'],
                            productDescription: data.data['description'],
                            productPrice: data.data['product_price']['price'],
                            productImages: data.data['image'],
                            productAttributes: data.data['attributes']};
          
          $ionicSlideBoxDelegate.update();
    });
  }

  getProduct();

  
    $scope.addProductToCart = function(product_id,product_attribute){
       if(!$auth.isAuthenticated()){
        var item = ngCart.getItemById(String(product_id));
        if(item === false || angular.isUndefined(product_attribute)){
          alertsFactory.showAlert( "Selecione um Tamanho!");
          return;
        }
        alertsFactory.showAlert( "Adicionado com sucesso!");
        return;
       }
       var item = ngCart.getItemById(String(product_id));
        if(item === false || angular.isUndefined(product_attribute)){
          alertsFactory.showAlert( "Selecione um Tamanho!");
          return;
        }
        var quantity = item.getQuantity();
      cartFactory.addProduct(product_id,product_attribute,quantity).then(function successCallback(data){
        console.log(data);
        alertsFactory.showAlert( "Adicionado com sucesso!");
      },function errorCallback(data){
        console.log(data);
      })
    }

  $scope.favoriteProduct = function(id_product,product_attribute,product){
    wishlistFactory.favoriteProduct(id_product,product_attribute,product)
      .then(function successCallback(response){
          alertsFactory.showAlert( "Adicionado aos Favoritos!");
          console.log(response.data);
          
      })    
    }
    
}]);

})();