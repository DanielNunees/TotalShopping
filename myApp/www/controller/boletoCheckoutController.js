(function() {
    'use strict';
angular.module('app')
.controller('boletoCheckoutController', ['$scope', '$http','$ionicNavBarDelegate','$window','paymentCheckout','userDataFactory','$ionicPopup','$interval','ngCart', function($scope,$http,$ionicNavBarDelegate,$window,paymentCheckout,userDataFactory,$ionicPopup,$interval,ngCart){
  var SenderHash;
  $scope.method = 0;
  $scope.user = {};

  //var theTime = new Date().toLocaleTimeString();
    $interval(function () {
        paymentCheckout.resetSessionId();
        paymentCheckout.getSession();
        console.log('resetando a seção');
    }, 3600000);

  $scope.checkout = function(user){
    console.log(user);
    $scope.showLoading(); //Loading animation...
    SenderHash = PagSeguroDirectPayment.getSenderHash();
    paymentCheckout.boletoCheckout(SenderHash).then(function successCallback(response){
      ngCart.empty();
      console.log(response.data);
      $scope.hideLoading();
      var alertPopup = $ionicPopup.alert({
        title: 'Finalizado',
        template: 'Sua compra foi efetuada com sucesso!',
      });
      delete $scope.user;
      $scope.teste();
      paymentCheckout.resetSessionId();

      paymentCheckout.getSession().then(function successCallback(response){
        console.log('ok');
      });

    },function errorCallback(response){
      console.log(response);
    });

  }
}]);
})();