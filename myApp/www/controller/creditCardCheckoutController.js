app.controller('creditCardCheckoutController', ['$scope', '$http','$ionicHistory','$ionicNavBarDelegate','$window','paymentCheckout','userDataFactory','$ionicPopup', function($scope,$http,$ionicHistory,$ionicNavBarDelegate,$window,paymentCheckout,userDataFactory,$ionicPopup){
  var checkoutData={};
  var SenderHash;
  $scope.method = 0;
  $scope.user = {};

  var loadUserData = function(){
    userDataFactory.loadUserData().then(function successCallback(response) {
        checkoutData.userData = response.address[0];
        checkoutData.userBirth = response.user[0];
        $scope.showLoading();//Loading animation... 
        }, function errorCallback(response) {
          /* Tratamento de erros*/
          //error 400 - No content
          if(response.status==400){
            console.log(response.status);
          }
          else{
            console.log(response);
          }
          /* Fim Tratamento de erros*/
          console.log(response.data);
        });
  }
  $scope.checkout = function(){
    loadUserData();
    var param = {
      cardNumber: $scope.user.cardnumber,
      cardBin:  $scope.user.cardnumber.slice(0,6),
      cvv: $scope.user.cvv,
      expirationMonth: $scope.user.expirationMonth,
      expirationYear: $scope.user.expirationYear,
      success: function(response) {
        //token gerado, esse deve ser usado na chamada da API do Checkout Transparente
        var cart = angular.fromJson($window.localStorage ['cart']);
        checkoutData.cart = JSON.parse(cart);
        checkoutData.cpf = '15600944276'; //valid teste cpf 15600944276
        checkoutData.creditCardToken = response.card.token;
        checkoutData.name = $scope.user.name;
        checkoutData.SenderHash = PagSeguroDirectPayment.getSenderHash();

        paymentCheckout.creditCardCheckout(checkoutData).then(function successCallback(response) {
          paymentCheckout.resetSessionId();
          console.log(response.data);
          $scope.hideLoading();
          var alertPopup = $ionicPopup.alert({
            title: 'Finalizado',
              template: 'Sua compra foi efetuada com sucesso!',
          });
          $scope.user = {};

        },function errorCallback(response) {
          $scope.hideLoading();
          var alertPopup = $ionicPopup.alert({
            title: 'Error 401',
            template: 'Alguma coisa deu errado!',
          });
          // Tratamento de erros
          //error 400 - No content
          if(response.status==400){
            $scope.isEmpty = true;
          }
          else{$scope.isEmpty=false;}
          //Fim Tratamento de erros
          console.log(response.data);
        });
      },
      error: function(response) {
        //tratamento do erro
        $scope.hideLoading();

        //if(Object.keys(response.errors)==1000)
        console.log(response);
        var error = Object.keys(response.errors);
        
        switch(error[0]) {
            case '10000':
                 alertPopup = $ionicPopup.alert({
                      title: 'Error 10000',
                      template: 'Numero do cartão inválido',
                  });
                break;
            case '10001' :
                 alertPopup = $ionicPopup.alert({
                      title: 'Error 10001',
                      template: 'Numero do cartão inválido',
                  });
                break;
              case '30405' :
               alertPopup = $ionicPopup.alert({
                    title: 'Error 30405',
                    template: 'Data do cartão invalida',
                });
              break;
            default: 
                 alertPopup = $ionicPopup.alert({
                      title: 'Error 1000',
                      template: 'Alguma coisa deu errado',
              });
        } 
      },
      complete: function(response) {
      //tratamento comum para todas chamadas
      }
    }
    //parâmetro opcional para qualquer chamada
    param.cardBin = $scope.user.cardnumber.slice(0,6);
    PagSeguroDirectPayment.createCardToken(param);
  }
}]);