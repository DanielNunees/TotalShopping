(function() {
    'use strict';
angular.module('app')
.controller('boletoCheckoutController', ['$scope', '$http','$ionicNavBarDelegate','$window','paymentCheckout','userDataFactory','$ionicPopup','$interval','ngCart', function($scope,$http,$ionicNavBarDelegate,$window,paymentCheckout,userDataFactory,$ionicPopup,$interval,ngCart){
  var checkoutData={};
  var SenderHash;
  $scope.method = 0;
  $scope.user = {};

  //var theTime = new Date().toLocaleTimeString();
    $interval(function () {
        paymentCheckout.resetSessionId();
        paymentCheckout.getSession();
        console.log('resetando a seção');
    }, 3600000);

  $scope.checkout = function(){
    $scope.showLoading(); //Loading animation...
    userDataFactory.loadUserData().then(function successCallback(response) {

      
      checkoutData.cart = ngCart.getCart();
      checkoutData.userData = response.address[0];
      checkoutData.userBirth = response.user[0];
      //checkoutData.cpf = $scope.user.cpf;
      checkoutData.customer_id = localStorage.id;
      checkoutData.cpf = '15600944276'; //valid teste cpf 15600944276
      checkoutData.name = $scope.user.name;
      checkoutData.SenderHash = PagSeguroDirectPayment.getSenderHash();

    paymentCheckout.boletoCheckout(checkoutData).then(function successCallback(response){

      console.log(response.data);
      $scope.hideLoading();
      var alertPopup = $ionicPopup.alert({
        title: 'Finalizado',
        template: 'Sua compra foi efetuada com sucesso!',
      });
      $scope.user = {};
      paymentCheckout.resetSessionId();

      paymentCheckout.getSession().then(function successCallback(response){
        console.log('ok');
      });

    },function errorCallback(response){
      console.log(response);
    });
    },function errorCallback(response) {
      /* Tratamento de erros*/

      /* Fim Tratamento de erros*/
      console.log(response);
    }); 
  }
}]);
})();