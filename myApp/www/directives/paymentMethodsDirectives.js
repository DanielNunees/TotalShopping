(function() {
    'use strict';
angular.module('app')
.directive('boletoMethod',function(){
	return {
		restrict:'E',
		templateUrl: '../templates/paymentMethodBoleto.html',
		controller: 'boletoCheckoutController'
	};
});

angular.module('app')
.directive('creditcardMethod',function(){
	return{
		restrict:'E',
		controller:'creditCardCheckoutController',
		templateUrl:'../templates/paymentMethodCreditCard.html'
	};
});


})();