app.directive('boletoMethod',function(){
	return {
		restrict:'E',
		templateUrl: '../templates/paymentMethodBoleto.html',
		controller: 'boletoCheckoutController'
	};
});

app.directive('creditcardMethod',function(){
	return{
		restrict:'E',
		controller:'creditCardCheckoutController',
		templateUrl:'../templates/paymentMethodCreditCard.html'
	};
});