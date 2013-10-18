
$ = jQuery.noConflict();

angular.module('mindsUploader', ['services.Elgg', 'services.Kaltura', 'mindsApp.directives'], function($routeProvider) {

    var templatesPath = serverUrl +"mod/archive/angular/app/partials";

    $routeProvider.when('/', {
        templateUrl: templatesPath + '/upload.html',
        controller: UploadCtrl
    })
    .otherwise({
            redirectTo: '/'
    });
}).config(function ($httpProvider) {
    $httpProvider.defaults.transformRequest = function(data){
        if (data === undefined) {
            return data;
        }
            return $.param(data);
        }
});