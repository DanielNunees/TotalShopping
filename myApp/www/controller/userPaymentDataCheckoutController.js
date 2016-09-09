(function() {
    'use strict';
angular.module('app')
.controller('userPaymentDataCheckoutController',['$scope','$http','$ionicActionSheet', '$timeout','paymentCheckout','userDataFactory','$ionicNavBarDelegate','$window','$ionicPopup','$ionicLoading',function($scope,$http,$ionicActionSheet, $timeout,paymentCheckout,userDataFactory,$ionicNavBarDelegate,$window,$ionicPopup,$ionicLoading){
	$scope.$on("$ionicView.beforeEnter", function(event, data){
    	$ionicNavBarDelegate.showBackButton(true);
    	$scope.loadUserData1();
    	$scope.showLoading();

    	paymentCheckout.getSession().then(function successCallback(response) {

	  		//PagSeguroDirectPayment.setSessionId(response.data);
	  		var SenderHash = PagSeguroDirectPayment.getSenderHash();
	  		$scope.hideLoading();
        },function errorCallback(response) {
        	/* Tratamento de erros*/
	      	//error 400 - No content
	      	if(response.status == 500){
	            var alertPopup = $ionicPopup.alert({
	            	title: 'Error 500',
	              	template: 'Serve Error! The checkout will not be processed!',
	            });
          	}
	      	if(response.status==400){
	      		$scope.isEmpty = true;
	      	}
	      	else{$scope.isEmpty=false;}
	      	/* Fim Tratamento de erros*/
	      	$scope.hideLoading();
         	console.log(response);
        });
  	});
	$scope.user_data = {};
  	$scope.method = 1;

  	$scope.showLoading = function() {
	    $ionicLoading.show({
	      template: '<ion-spinner></ion-spinner>'
	    }).then(function(){
	       console.log("The loading indicator is now displayed");
	    });
	};

  	$scope.hideLoading = function(){
    	$ionicLoading.hide().then(function(){
       		console.log("The loading indicator is now hidden");
    	});
  	};	

	$scope.show = function() {

	   // Show the action sheet
	   var hideSheet = $ionicActionSheet.show({
	     buttons: [
	       { text: 'Cartão de Crédito' },
	       { text: 'Boleto Bancário' },
	       { text: 'Saldo do PagSeguro' },
	       { text: 'Débito Online' }
	     ],
	     titleText: 'Métodos de Pagamento',
	     cancelText: 'Cancel',
	     cancel: function() {
	          // add cancel code..
	        },
	     buttonClicked: function(index) {
	     	paymentCheckout.resetSessionId();
	     	paymentCheckout.getSession();
	     	$scope.method = index;
	       return true;
	     }
	   });
	 };

	 $scope.loadUserData1 = function(){
		userDataFactory.loadUserData().then(function successCallback(response) {
      	$scope.isEmpty = false;
      	$scope.userData = response.address[0];
      	$scope.userBirth = response.user[0];
      	$scope.user_data = response.address[0];
        }, function errorCallback(response) {
        	/* Tratamento de erros*/
	      	//error 400 - No content
	      	if(response.status==400){
	      		$scope.isEmpty = true;
	      	}
	      	else{$scope.isEmpty=false;}
	      	/* Fim Tratamento de erros*/
         	console.log(response.data);
        });
	}

 	
	
}])
})();