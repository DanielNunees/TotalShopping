// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
var app = angular.module('starter', ['ionic','ngCookies','ngCart','satellizer','ngMask'])

app.config(function($stateProvider, $urlRouterProvider,$authProvider) {

  $authProvider.loginUrl = 'http://127.0.1.1/laravel/public/user/auth';
  $authProvider.signupUrl = 'http://127.0.1.1/laravel/public/user/register';
  // For any unmatched url, redirect to /state1
  $urlRouterProvider.otherwise("/home");
  
    // Now set up the states
  $stateProvider
    .state('home', {
      url: "/home",
      controller: 'homeController',
      templateUrl: "view/home.html"
    })
    
    .state('product', {
      url: "/product/:productId",
      controller: 'productController',
      templateUrl: "view/product.html"
    })

    .state('cart', {
      url: "/cart",
      controller: 'cartController',
      templateUrl: "view/cart.html"
    })

    .state('userRegister', {
      url: "/user/register",
      controller: 'userLoginController',
      templateUrl: "view/userRegister.html"
    })

    .state('userLogin', {
      url: "/user/login",
      controller: 'userLoginController',
      templateUrl: "view/userLogin.html"
    })

    .state('userDashboard', {
      url: "/user/dashboard",
      controller: 'userDashboardController',
      templateUrl: "view/userDashboard.html"
    })

    .state('wishlist', {
      url: "/user/wishlist",
      controller: 'wishlistController',
      templateUrl: "view/wishlist.html"
    })

    .state('userDataCheckout', {
      url: "/user/dataCheckout",
      controller: 'userDashboardController',
      templateUrl: "view/userDataCheckout.html"
    })

    .state('userPaymentDataCheckout', {
      url: "/user/PaymentdataCheckout",
      controller: 'userPaymentDataCheckoutController',
      templateUrl: "view/userPaymentDataCheckout.html"
    })

});

app.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    if(window.cordova && window.cordova.plugins.Keyboard) {
      // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
      // for form inputs)
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);

      // Don't remove this line unless you know what you are doing. It stops the viewport
      // from snapping when text inputs are focused. Ionic handles this internally for
      // a much nicer keyboard experience.
      cordova.plugins.Keyboard.disableScroll(true);
    }
    if(window.StatusBar) {
      StatusBar.styleDefault();
    }
  });
  
});