app.controller('boletoCheckoutController', ['$scope', '$http','$ionicHistory','$ionicNavBarDelegate','$window','paymentCheckout','userDataFactory','$ionicPopup','$timeout','$interval', function($scope,$http,$ionicHistory,$ionicNavBarDelegate,$window,paymentCheckout,userDataFactory,$ionicPopup,$timeout,$interval){
  var checkoutData={};
  var SenderHash;
  $scope.method = 0;
  $scope.user = {};

  //var theTime = new Date().toLocaleTimeString();
    $interval(function () {
        paymentCheckout.resetSessionId();
        paymentCheckout.getSession();
    }, 108000);

  $scope.checkout = function(){
    $scope.showLoading(); //Loading animation...
    userDataFactory.loadUserData().then(function successCallback(response) {
      var cart = angular.fromJson($window.localStorage ['cart']);
      checkoutData.cart = JSON.parse(cart);
      checkoutData.userData = response.address[0];
      checkoutData.userBirth = response.user[0];
      //checkoutData.cpf = $scope.user.cpf;
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
      //error 400 - No content
      if(response.status==400){
        $scope.isEmpty = true;
      }
      else{$scope.isEmpty=false;}
      /* Fim Tratamento de erros*/
      console.log(response);
    }); 
  }
}]);