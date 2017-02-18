(function() {
    'use strict';
  angular.module('app').config(function($stateProvider, $urlRouterProvider,$authProvider,$ocLazyLoadProvider) {

  $authProvider.loginUrl = 'http://127.0.1.1/laravel/public/user/auth';
  $authProvider.signupUrl = 'http://127.0.1.1/laravel/public/user/register';
  // For any unmatched url, redirect to /state1
  $urlRouterProvider.otherwise("/home");

  $ocLazyLoadProvider.config({
        'debug': false, // For debugging 'true/false'
        'events': false, // For Event 'true/false'
    });
  
    // Now set up the states
  $stateProvider

    .state('home', {
      url: "/home", // root route
      templateUrl: "view/home.html",
      controller: 'homeController',
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/homeController.js','factory/productFactory.js' ])
        }]
      }
    })
        
    .state('product', {
      url: "/product/:productId",
      controller: 'productController',
      templateUrl: "view/product.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/productController.js','factory/productFactory.js','factory/cartFactory.js','factory/wishlistFactory.js'])
        }]
      }
    })

    .state('cart', {
      url: "/cart",
      templateUrl: "view/cart.html",
      controller: 'cartController',
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/cartController.js','factory/cartFactory.js'])
        }]
      }
    })

    .state('userRegister', {
      url: "/user/register",
      controller: 'userController',
      templateUrl: "view/userRegister.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/userController.js','factory/userFactory.js'])
        }]
      }
    })

    .state('userLogin', {
      url: "/user/login",
      controller: 'userController',
      templateUrl: "view/userLogin.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/userController.js','factory/userFactory.js'])
        }]
      }
    })

    .state('userDashboard', {
      url: "/user/dashboard",
      controller: 'dashboardController',
      templateUrl: "view/userDashboard.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/dashboardController.js','factory/dashboardFactory.js','factory/userFactory.js','factory/transactionFactory.js'])
        }]
      }
    })

    .state('wishlist', {
      url: "/user/wishlist",
      controller: 'wishlistController',
      templateUrl: "view/wishlist.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/wishlistController.js','factory/wishlistFactory.js'])
        }]
      }
    })

    .state('Checkout', {
      url: "/user/Checkout",
      controller: 'checkoutController',
      templateUrl: "view/checkout.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/checkoutController.js','factory/cartFactory.js','factory/userFactory.js','factory/checkoutFactory.js',
                                   'lib/PagSeguro/pagseguro.directpayment.js'])
        }]
      }
    })

    .state('multiStore', {
      url: "/multiStore",
      controller: 'multiStoreController',
      templateUrl: "view/multiStore.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/multiStoreController.js','factory/multiStoreFactory.js'])
        }]
      }
    })

    .state('store', {
      url: "/store/:idStore",
      controller: 'storeController',
      templateUrl: "view/store.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/storeController.js','factory/multiStoreFactory.js'])
        }]
      }
    })

    .state('categories', {
      url: "/categories",
      controller: 'categoriesController',
      templateUrl: "view/categories.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/categoriesController.js','factory/productCategoryFactory.js'])
        }]
      }
    })

    .state('category', {
      url: "/category/:categoryId",
      controller: 'categoryController',
      templateUrl: "view/category.html",
      resolve: {
        loadCtrl: ['$ocLazyLoad', function($ocLazyLoad){
          return $ocLazyLoad.load(['controller/categoryController.js','factory/productCategoryFactory.js'])
        }]
      }
    })

  })

})();