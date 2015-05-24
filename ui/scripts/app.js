/*jshint unused: vars */
define(['angular', 'controllers/main',  'controllers/docs', 'controllers/governance']/*deps*/, function (angular, MainCtrl, AboutCtrl)/*invoke*/ {
  'use strict';

  /**
   * @ngdoc overview
   * @name orgApp
   * @description
   * # orgApp
   *
   * Main module of the application.
   */
  return angular
    .module('orgApp', ['orgApp.controllers.MainCtrl',
    'orgApp.controllers.DocsCtrl',
	'orgApp.controllers.GovernanceCtrl',
	/*angJSDeps*/
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngRoute',
    'ngAnimate',
    'ngTouch'
  ])
    .config(function ($routeProvider) {
      $routeProvider
        .when('/', {
          templateUrl: 'views/main.html',
          controller: 'MainCtrl'
        })
        .when('/docs', {
          templateUrl: 'views/docs.html',
          controller: 'DocsCtrl'
        })
        .when('/code', {
          templateUrl: 'views/code.html',
          controller: 'CodeCtrl'
        })
        .when('/governance', {
          templateUrl: 'views/governance.html',
          controller: 'GovernanceCtrl'
        })
        .otherwise({
          redirectTo: '/'
        });
    });
});