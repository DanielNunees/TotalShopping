(function() {
    'use strict';
angular.module('app')
.directive('boletoMethod',function(){
	return {
		restrict:'E',
		templateUrl: '../templates/paymentMethodBoleto.html',
		controller: 'checkoutController',
	};
});

angular.module('app')
.directive('creditcardMethod',function(){
	return{
		restrict:'E',
		controller:'checkoutController',
		templateUrl:'../templates/paymentMethodCreditCard.html',
	};
});


})();