app.controller('cartController', ['$scope','$ionicNavBarDelegate',  function($scope,$ionicNavBarDelegate){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
      $ionicNavBarDelegate.showBackButton(true);
    });
 
	$scope.options = {
	  loop: false,
	  effect: 'slide',
	  speed: 500,
	  swipeHandler: false,
	  uniqueNavElements: false,
	  pagination: false,
	}

	$scope.$on("$ionicSlides.sliderInitialized", function(event, data){
	  // data.slider is the instance of Swiper
	  $scope.slider = data.slider;
	});

	$scope.$on("$ionicSlides.slideChangeStart", function(event, data){
	  console.log('Slide change is beginning');
	});

	$scope.$on("$ionicSlides.slideChangeEnd", function(event, data){
	  // note: the indexes are 0-based
	  $scope.activeIndex = data.activeIndex;
	  $scope.previousIndex = data.previousIndex;
	});



}]);