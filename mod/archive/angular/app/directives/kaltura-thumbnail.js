/**
 * kaltura-thumbnail directive.
 */

angular.module('mindsApp.directives', []).directive('kalturaThumbnail', function () {
    return {
        restrict: "A,E",
        replace: false,
        transclude: false,
        template: '<img src="" />',
        link: function ($scope, element, attrs, controller) {
            var unwatch = $scope.$watch('attrs.entryid', function(entryid) {
                if (attrs.entryid)
                {
                    var entryId = attrs.entryid;
                    var imgElement = jQuery(element).find('img');

                    // Set global configuration.
                    var configObject = $scope[attrs.kalturaOptions];
                    var serviceUrl = configObject.serviceUrl;
                    var pid = configObject.pid;

                    // Build thumbnail url.
                    var width = parseInt(attrs.width, 10) || element.css('width') || 200;
                    var height = parseInt(attrs.height, 10) || element.css('height') || 150;
                    var thumbnailUrl = serviceUrl + '/p/' + pid + '/thumbnail/entry_id/' +
                        entryId + '/width/' + width + '/height/' + height;

                    // Set img attributes.
                    imgElement.attr("src", thumbnailUrl);
                    imgElement.attr('width', width);
                    imgElement.attr('height', height);
                    unwatch();
                }

            }, true);
        }
    };
});