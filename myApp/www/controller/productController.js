(function() {
  'use strict';
angular.module('app')
.controller('productController', ['$scope','$stateParams','$ionicPopup','$ionicNavBarDelegate','$ionicSlideBoxDelegate','productFactory','cartFactory','ngCart','$auth', function($scope,$stateParams,$ionicPopup,$ionicNavBarDelegate,$ionicSlideBoxDelegate,productFactory,cartFactory,ngCart,$auth){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicNavBarDelegate.showBackButton(true);
  });
  $scope.added = false;
  var guest;
  
  $scope.isAuthenticated = function() {
    return $auth.isAuthenticated();
  };


  productFactory.getProduct($stateParams.productId).then(function successCallback(data){
    $scope.product = {};
        $scope.product = {product_name: data['description'][0]['name'],
                          product_id: data['description'][0]['id_product'],
                          product_description: data['description'][0]['description'],
                          product_price: data['description'][0]['product_price']['price'],
                          product_images: data['images'],
                          product_attributes: data['attributes']};
        
        $ionicSlideBoxDelegate.update();
  })

  $scope.addProductToCart = function(product_id,product_attribute){
     var item = ngCart.getItemById(String(product_id));
      if(item === false || angular.isUndefined(product_attribute)){
        var alertPopup = $ionicPopup.alert({
            title: 'Selecione uma Opção',
            template: 'Selecione o Tamanho'
          })
        return;
      }
      var quantity = item.getQuantity();
      isGuest();
    cartFactory.addProduct(product_id,product_attribute,quantity).then(function successCallback(data){
      console.log(data);
    },function errorCallback(data){
      console.log(data);
    })
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
  
  $scope.favoriteProduct = function(id_product,product_attribute){
    productFactory.favoriteProduct(id_product,product_attribute)
      .then(function successCallback(response){
        $scope.added = true;
          console.log(response.data);
          var alertPopup = $ionicPopup.alert({
            template: 'Adicionado aos favoritos!'
          });
      })    
    }
    
}]);

})();