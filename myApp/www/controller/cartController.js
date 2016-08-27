(function() {
    'use strict';
angular.module('app')
.controller('cartController', ['$scope','$ionicNavBarDelegate','$http','$httpParamSerializerJQLike','$ocLazyLoad',  function($scope,$ionicNavBarDelegate,$http,$httpParamSerializerJQLike,$ocLazyLoad){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
      console.log('ok');
    });
    console.log('ok');


$ocLazyLoad.load('/controller/cartController.js');
}]);
})();