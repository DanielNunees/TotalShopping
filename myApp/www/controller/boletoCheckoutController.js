app.controller('boletoCheckoutController', ['$scope', '$http','$ionicHistory','$ionicNavBarDelegate','$window','paymentCheckout','userDataFactory','$ionicPopup', function($scope,$http,$ionicHistory,$ionicNavBarDelegate,$window,paymentCheckout,userDataFactory,$ionicPopup){
  $scope.lixo = 'funcionou bb! chupa';
  var checkoutData={};
  var SenderHash;
  $scope.method = 0;
  $scope.user = {};
  

  
  
  $scope.checkout = function(){
    //loadUserData();
    var a = $scope.loadUserData();
    console.log(a);

    console.log('ta vindo pro boleto controller');

  
  }

  

}]);