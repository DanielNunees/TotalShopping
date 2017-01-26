(function() {
    'use strict';
angular.module('app')
.controller('homeController', ['$scope','$ionicHistory','$ionicNavBarDelegate','productFactory', function($scope,$ionicHistory,$ionicNavBarDelegate,productFactory){
  
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicHistory.clearCache();
    $ionicHistory.clearHistory();
    $ionicNavBarDelegate.showBackButton(false);
  });

  productFactory.getAllProducts().
    then(function successCallback(data){
      $scope.product = data.data;
    },function errorCallback(response){
      console.log(response);
  });

}]);
})();