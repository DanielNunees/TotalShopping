(function() {
    'use strict';
angular.module('app')
.controller('cartController', ['$scope','$ionicNavBarDelegate','$http','$httpParamSerializerJQLike','$ocLazyLoad',  function($scope,$ionicNavBarDelegate,$http,$httpParamSerializerJQLike,$ocLazyLoad){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
    });


$ocLazyLoad.load('/controller/cartController.js');
}]);
})();