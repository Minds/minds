/**
 * A node service.
 * User: ron
 * Date: 7/4/13
 * Time: 3:03 PM
 * To change this template use File | Settings | File Templates.
 */
angular.module('services.Node', []);
angular.module('services.Node').factory('Node', ['$http', '$q', function($http, $q) {

    var nodeService = {};
    var baseUrl = 'http://minds.localhost/node';
    var format = '.json';

    nodeService.getAll = function() {
        var deferred = $q.defer();

        var url = baseUrl + format;
        $http({method: 'GET', url: url}).
            success(function(data, status, headers, config) {
                deferred.resolve(data.list);
            }).
            error(function(data, status, headers, config) {
                console.log('error: ' + data);
                deferred.reject();
            });

        return deferred.promise;
    }

    nodeService.get = function(nid) {
        var url = baseUrl + '/' + nid + format;
        $http({method: 'GET', url: url}).
            success(function(data, status, headers, config) {
                return data;
            }).
            error(function(data, status, headers, config) {
                console.log('error: ' + data);
                return null;
            });
    }

    nodeService.add = function(node) {
        var url = baseUrl;
        var deferred = $q.defer();

        $http({method: 'POST', url: url, data: node}).
            success(function(data, status, headers, config) {
                deferred.resolve(data);
            }).
            error(function(data, status, headers, config) {
                console.log('error: ' + data);
                deferred.reject();
            });

        return deferred.promise;
    }

    nodeService.update = function(node) {
        var url = baseUrl + '/' + node.nid;
        $http({method: 'PUT', url: url, data: node}).
            success(function(data, status, headers, config) {
                return data;
            }).
            error(function(data, status, headers, config) {
                console.log('error: ' + data);
                return null;
            });
    }

    nodeService.delete = function(nid) {
        var url = baseUrl + '/' + nid;
        $http({method: 'DELETE', url: url}).
            success(function(data, status, headers, config) {
                return data;
            }).
            error(function(data, status, headers, config) {
                console.log('error: ' + data);
                return null;
            });
    }

    return nodeService;

}]);