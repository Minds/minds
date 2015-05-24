define(function(require) {

	'use strict';

	var angular = require('angular'),
		controllers = angular.module('app.controllers', []);

	controllers.controller('DefaultCtrl', require('controllers/DefaultCtrl'));

	return controllers;

});