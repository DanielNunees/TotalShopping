(function() {
    'use strict';
angular.module('app')
.controller('wishlistController', ['$scope', '$http','$auth','$httpParamSerializerJQLike','$ionicPopup','$location','$ionicHistory','$ionicNavBarDelegate','wishlistFactory', function($scope,$http,$auth,$httpParamSerializerJQLike,$ionicPopup,$location,$ionicHistory,$ionicNavBarDelegate,wishlistFactory){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicNavBarDelegate.showBackButton(true);
  });
  $scope.product = [];

  $scope.isAuthenticated = function() {
      return $auth.isAuthenticated();
    };



  wishlistFactory.getWishlist().then(function successCallback(response){
    $scope.product = [];
      for(var i=0;i<response.image.length;i++){
        var item={};
        item.id_product = response.id_product[i];
        item.name = response.name[i].name;
        item.price = response.price[i].price;
        item.image = response.image[i].image;

        $scope.product.push(item);
      }
  },function errorCallback(response) {
    /* Tratamento de erros*/
    //error 204 - No content
    console.log(response.data);
    if(response.status == 404){
      var alertPopup = $ionicPopup.alert({
        title: 'Error 404',
        template: 'Sua lista está vazia',
      });
    }else if(response.status == 400){
      var alertPopup = $ionicPopup.alert({
      title: 'Error 400',
      template: 'Whislist not created',
    });
    }
    /* Fim Tratamento de erros*/
    
    //$location.url('/user/home');
  });

  $scope.remove = function(index,id_product){
    wishlistFactory.removeProduct(id_product).then(function successCallback(response){
      console.log(response.data);
      $scope.product.splice(index,1);
    },function errorCallback(response) {
      console.log(response);
    });
  }
    
  }])
})();

