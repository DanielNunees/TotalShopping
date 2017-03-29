(function() {
    'use strict';
	angular.module('app').
	factory('checkoutErrosFactory', function($ionicPopup) {
    	var showAlert = function(title,text) {
	    	var alertPopup = $ionicPopup.alert({
	       	title: title,
	       	template: text,
		    });
	    }

    	return {
    		error: function(error_code){
    			if(angular.isArray(error_code.errors.error)){
    				error_code = error_code.errors.error[0].code;
    				switch(error_code){
	    				case '53017':
	    					showAlert("CPF inválido");
	    					break;
	    				case '53019':
	    					showAlert("Código DDD inválido");
	    					break;
	    				case '53021':
	    					showAlert("Número de telefone inválido");
	    					break;
	    				default:
	    					showAlert("Error1");

    				}
    				return;
    			}
    			error_code = error_code.errors.error.code;
    			switch(error_code){
    				case '53017':
    					showAlert("CPF inválido");
    					break;
    				default:
    					showAlert("Error");
    			}
    			console.log(error_code);
    		},

    		creditCardError: function(error){
    			var code = Object.keys(error.errors);

    			switch(code[0]){
    				case '10000':
    					showAlert("Dados do cartão estão incorretos");
    					break;
    				case '30405':
    					showAlert("Data de validade do cartão inválida");
    					break;
    				case '30400':
    					showAlert("Dados do cartão estão incorretos");
    					break;
    				default:
    					showAlert("Error");
    			}
    		}
    	}
  	});

})();