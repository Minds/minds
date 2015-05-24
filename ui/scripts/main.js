/*jshint unused: vars */
require.config({
  paths: {
    angular: '../vendors/angular/angular',
    'angular-animate': '../vendors/angular-animate/angular-animate',
    'angular-cookies': '../vendors/angular-cookies/angular-cookies',
    'angular-mocks': '../vendors/angular-mocks/angular-mocks',
    'angular-resource': '../vendors/angular-resource/angular-resource',
    'angular-route': '../vendors/angular-route/angular-route',
    'angular-sanitize': '../vendors/angular-sanitize/angular-sanitize',
    'angular-touch': '../vendors/angular-touch/angular-touch',
    bootstrap: '../vendors/bootstrap/dist/js/bootstrap'
  },
  shim: {
    angular: {
      exports: 'angular'
    },
    'angular-route': [
      'angular'
    ],
    'angular-cookies': [
      'angular'
    ],
    'angular-sanitize': [
      'angular'
    ],
    'angular-resource': [
      'angular'
    ],
    'angular-animate': [
      'angular'
    ],
    'angular-touch': [
      'angular'
    ],
    'angular-mocks': {
      deps: [
        'angular'
      ],
      exports: 'angular.mock'
    }
  },
  priority: [
    'angular'
  ],
  packages: [

  ]
});

//http://code.angularjs.org/1.2.1/docs/guide/bootstrap#overview_deferred-bootstrap
window.name = 'NG_DEFER_BOOTSTRAP!';

require([
  'angular',
  'app',
], function(angular, app) {
  'use strict';
  /* jshint ignore:start */
  var $html = angular.element(document.getElementsByTagName('html')[0]);
  /* jshint ignore:end */
  angular.element().ready(function() {
    angular.bootstrap(document,[app.name]);
  });
});