/**
 * Gallery controller.
 * @param $scope
 * @param Node node service.
 * @constructor
 */

angular.module('mindsApp.controllers', []).controller('GalleryCtrl', ['$scope', 'Node',
    function($scope) {
        $scope.videoList = [];

        // Gallery directive configuration object.
        $scope.configObject = {
            serviceUrl: serviceUrl,
            pid: pid
        };

        $scope.listVideos = function(filters) {

            var filters = {
                type: 'video'
            };

            Node.getAll(filters).then(
                function(nodes) {
                    // Add entry id on each nodes.
                    $scope.videoList = nodes;
                }
            );
        };
    }
]);