(function() {
    'use strict';
angular.module('app')
.controller('homeController', ['$scope','$ionicHistory','$ionicNavBarDelegate','productFactory','$auth','$ionicTabsDelegate','$state', function($scope,$ionicHistory,$ionicNavBarDelegate,productFactory,$auth,$ionicTabsDelegate,$state){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicHistory.clearCache();
    $ionicHistory.clearHistory();
    $ionicNavBarDelegate.showBackButton(false);
    $ionicTabsDelegate.select(0);
  }); 

  $scope.products = [];
  $scope.endData = true;

  $scope.isAuthenticated = function(){
    return $auth.isAuthenticated();
  };

  $scope.selectTabWithIndex = function(state,index) {
    if(state.indexOf("userDashboard")>-1&&!$auth.isAuthenticated()){ 
      state = 'userLogin';
    }

    if(state.indexOf("wishlist")>-1&&!$auth.isAuthenticated()){ 
      state = 'userLogin';
    }
    
    $ionicTabsDelegate.select(index);
    $state.transitionTo(state);
  };

  $scope.getAllProducts = function(){
    productFactory.getAllProducts().
      then(function successCallback(data){
        productFactory.nextPage();
        //if(data.data['products'].length>0){
          angular.forEach(data.data['products'], function(value1, key1) {
              $scope.products.push({productPrice:value1['product_price']['price'],
                              productName:value1['name'],
                              productImages: value1['image'][0],
                              productId: value1['id_product']
                              });
          });
        //}
        if($scope.products.length>=data.data['max']){
          $scope.endData = false;
        }
        $scope.$broadcast('scroll.infiniteScrollComplete');
      },function errorCallback(response){
        console.log(response);
    });
  }
}]);
})();