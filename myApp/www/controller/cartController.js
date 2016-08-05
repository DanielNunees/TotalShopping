app.controller('cartController', ['$scope','$ionicNavBarDelegate','$http','$httpParamSerializerJQLike',  function($scope,$ionicNavBarDelegate,$http,$httpParamSerializerJQLike){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
    });
}]);