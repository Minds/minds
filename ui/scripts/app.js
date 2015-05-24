define(['angular', 'controllers/controllers', 'angular-route'], function (angular){
  'use strict';

  /**
   * @ngdoc overview
   * @name orgApp
   * @description
   * # orgApp
   *
   * Main module of the application.
   */
  var app= angular.module('minds', ['app.controllers', 'ngRoute']);
  
  
  app.config(function ($routeProvider) {
      $routeProvider
        .when('/', {
          templateUrl: '/ui/templates/default.html',
          controller: 'DefaultCtrl'
        })
        .otherwise({
          redirectTo: '/'
        });
    });
    
    return app;
});