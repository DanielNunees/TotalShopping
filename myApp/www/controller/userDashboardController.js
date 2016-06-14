app.controller('userDashboardController', ['$scope','$auth','$location','$ionicHistory','$ionicSlideBoxDelegate', function($scope,$auth,$location,$ionicHistory,$ionicSlideBoxDelegate){
	$scope.slide = 0;

	/*if($auth.isAuthenticated()){
		$ionicHistory.removeBackView();
	}*/

	$scope.isAuthenticated = function() {
	  return $auth.isAuthenticated();
	};

	$scope.logout = function(){
		$auth.logout();
		$location.url('/user/home');
		//$ionicHistory.removeBackView();
	}

	$scope.slideChanged = function(index) {
		$ionicSlideBoxDelegate.slide(index, [300]);
	};

	$scope.slideHasChanged = function(index){
		$scope.slide = $ionicSlideBoxDelegate.currentIndex();
	}

	$scope.userData = function(){
		$http({
        method: 'POST',
        url: 'http://127.0.1.1/laravel/public/user/loadUserData',
        dataType: 'json',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: $httpParamSerializerJQLike({'id_customer':localStorage.id})
        
      }).then(function successCallback(response) {
      

        }, function errorCallback(response) {
         
        });
	}

}]);