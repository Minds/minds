$ = jQuery.noConflict();

window.onbeforeunload = function (event) {
  if(window.done){
	window.onbeforeunload = NULL;
	return true;
  }
  var message = 'Any uploads or unsaved changes will be lost by leaving this page. Are you sure you want to leave?';
  if (typeof event == 'undefined') {
    event = window.event;
  }
  if (event) {
    event.returnValue = message;
  }
  return message;
}

angular.module('mindsUploader', ['ngRoute', 'services.Elgg'], function($routeProvider) {

	var templatesPath = serverUrl + "mod/archive/angular/app/partials";

	$routeProvider.when('/', {
		templateUrl : templatesPath + '/upload.html',
		controller : UploadCtrl
	}).otherwise({
		redirectTo : '/'
	});
	
}).config(function($httpProvider) {
	$httpProvider.defaults.transformRequest = function(data) {
		if (data === undefined) {
			return data;
		}
		return $.param(data);
	};
}).filter('reverse', function() {
  return function(items) {
    return items.slice().reverse();
  };
}).filter('iif', function () {
   return function(input, trueValue, falseValue) {
        return input ? trueValue : falseValue;
   };
});
