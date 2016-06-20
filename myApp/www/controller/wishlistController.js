(function () {
app.controller('wishlistController', ['$scope', '$http','$auth','$httpParamSerializerJQLike','$ionicPopup','$location','$ionicHistory','$ionicNavBarDelegate', function($scope,$http,$auth,$httpParamSerializerJQLike,$ionicPopup,$location,$ionicHistory,$ionicNavBarDelegate){
  $scope.$on("$ionicView.beforeEnter", function(event, data){
    $ionicNavBarDelegate.showBackButton(true);
    $scope.loadWishlist();
  });
  
  $scope.product = [];
  console.log($ionicHistory.viewHistory());

  $scope.isAuthenticated = function() {
      return $auth.isAuthenticated();
    };

  $scope.login = function(){
    $location.url('/user/login');
  }

  $scope.loadWishlist = function(){
    if($auth.isAuthenticated()){
      $http({
          method: 'POST',
          url: 'http://127.0.1.1/laravel/public/wishlist',
          dataType: 'json',
          cache: true,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: $httpParamSerializerJQLike({'id_customer':localStorage.id})
        }).then(function successCallback(response) {          
            $scope.product = [];
            for(var i=0;i<response.data.image.length;i++){
              var item={};
              item.id_product = response.data.id_product[i];
              item.name = response.data.name[i].name;
              item.price = response.data.price[i].price;
              item.image = response.data.image[i].image;

              $scope.product.push(item);
            }
          },function errorCallback(response) {
            /* Tratamento de erros*/
            //error 204 - No content
            console.log(response.data);
            if(response.status == 404){
              var alertPopup = $ionicPopup.alert({
                title: 'Error 404',
                template: 'Sua lista está vazia',
              });
            }else if(response.status == 400){
              var alertPopup = $ionicPopup.alert({
              title: 'Error 400',
              template: 'Whislist not created',
            });
            }
            /* Tratamento de erros*/
            
            //$location.url('/user/home');
          });
      }
    }
    
    $scope.remove = function(index,id_product){
      console.log(id_product);
      if($auth.isAuthenticated()){
      $http({
          method: 'POST',
          url: 'http://127.0.1.1/laravel/public/removeWishlistProduct',
          dataType: 'json',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: $httpParamSerializerJQLike({'id_customer':localStorage.id,'id_product':id_product})
        }).then(function successCallback(response) {
            console.log(response.data);
            $scope.product.splice(index,1);
          },function errorCallback(response) {
            var alertPopup = $ionicPopup.alert({
              title: 'Nada ainda na sua lista',
              template: 'Crie agora sua lista de favoritos',
            });
            //$location.url('/user/home');
          });
      }
    }
    
}])
})();

