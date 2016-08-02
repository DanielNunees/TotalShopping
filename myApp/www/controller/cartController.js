app.controller('cartController', ['$scope','$ionicNavBarDelegate',  function($scope,$ionicNavBarDelegate){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
    });
 	
 	$scope.checkout = function(){
 		console.log('checkout');
 	}


}]);