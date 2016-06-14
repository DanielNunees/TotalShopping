(function () {
app.controller('wishlistController', ['$scope', '$http','$auth','$httpParamSerializerJQLike','$ionicPopup','$location','$ionicHistory', function($scope,$http,$auth,$httpParamSerializerJQLike,$ionicPopup,$location,$ionicHistory){
  $scope.$on("$ionicView.afterEnter", function(event, data){
     //
    
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
            var alertPopup = $ionicPopup.alert({
              title: 'Error 500',
              template: 'Algum coisa de errado aconteceu',
            });
            //$location.url('/user/home');
          });
      }
    }
    
    $scope.remove = function(index,id_product){
      if($auth.isAuthenticated()){
      $http({
          method: 'POST',
          url: 'http://127.0.1.1/laravel/public/removeWishlistProduct',
          dataType: 'json',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: $httpParamSerializerJQLike({'id_customer':localStorage.id,'id_product':id_product})
        }).then(function successCallback(response) {
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
$scope.loadWishlist();
   
}])
})();

