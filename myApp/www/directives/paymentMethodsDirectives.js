app.directive('boletoMethod',function(){
	return {
		restrict:'E',
		templateUrl: '../templates/paymentMethodBoleto.html'
	};
});

app.directive('creditcardMethod',function(){
	return{
		restrict:'E',
		templateUrl:'../templates/paymentMethodCreditCard.html'
	};
});