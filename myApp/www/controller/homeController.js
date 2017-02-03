(function() {
    'use strict';
angular.module('app')
.controller('homeController', ['$scope','$ionicHistory','$ionicNavBarDelegate','productFactory', function($scope,$ionicHistory,$ionicNavBarDelegate,productFactory){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicHistory.clearCache();
    $ionicHistory.clearHistory();
    $ionicNavBarDelegate.showBackButton(false);
  });
  $scope.products = [];

  $scope.getAllProducts = function(){
    productFactory.getAllProducts().
      then(function successCallback(data){

        if(data.data.length>0)
        angular.forEach(data.data, function(value1, key1) {
            $scope.products.push({productPrice:value1['description'][0]['product_price']['price'],
                            productName:value1['description'][0]['name'],
                            productImages: value1['images'][0]['image'],
                            productId: value1['description'][0]['id_product']
                            });
        });
        productFactory.nextPage();
        console.log(productFactory.getPage());
        $scope.$broadcast('scroll.infiniteScrollComplete');
      },function errorCallback(response){
        console.log(response);
    });
  }



}]);
})();