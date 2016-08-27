(function() {
    'use strict';
angular.module('app')
.directive('userAddress',function(){
	return {
		restrict:'E',
		templateUrl: '../templates/userAddress.html'
	};
});
})();