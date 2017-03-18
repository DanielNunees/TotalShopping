(function() {
    'use strict';
angular.module('app')
.directive('loadingDirective',function($ionicLoading){
	return {
	    link: function($scope, $element, attrs) {
	      var show = function() {
	        $ionicLoading.show({
		        //template:'Loading Data',
		        animation: 'fade-in',
		        duration: 2000
		      });
	      };

	      var hide = function() {
	        $ionicLoading.hide();
	      };
	      $scope.$on('loadingStatusActive', show);
	      $scope.$on('loadingStatusInactive', hide);
	      hide();
	    }
	}
});
})();