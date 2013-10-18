/**
 * kaltura-thumbnail directive.
 */
angular.module('mindsApp.directives', []).directive('kalturaThumbnail', function () {
    return {
        restrict: "A,E",
        replace: false,
        transclude: false,
        scope: {
            entryid: '=',
            kalturaOptions: '@',
        },
        compile: function (element, attrs) {
            var imgElement = jQuery('<img src="" class="img-rounded" />');

            var width = parseInt(attrs.width, 10) || element.css('width') || 200;
            var height = parseInt(attrs.height, 10) || element.css('height') || 150;
            imgElement.attr('width', width);
            imgElement.attr('height', height);
            imgElement.css('width', width + 'px');
            imgElement.css('height', height + 'px');
            jQuery(element).append(imgElement);

            return function ($scope, element, attrs, controller) {
                /* set the width of the img prior to entryid being received */

                var unwatch = $scope.$watch('entryid', function(entryId) {
                    if (entryId)
                    {
                        var imgElement = jQuery(element).find('img');

                        // Set global configuration.
                        var configObject = $scope.$parent[attrs.kalturaOptions];
                        var serviceUrl = configObject.serviceUrl;

                        var pid = configObject.pid;

                        // Build thumbnail url.
                        var thumbnailUrl = serviceUrl + '/p/' + pid + '/thumbnail/entry_id/' +
                            entryId + '/width/' + width + '/height/' + height + '/type/4';

                        // Set img attributes.
                        imgElement.attr("src", thumbnailUrl);
                        unwatch();
                    }

                }, true);
            }
        }
    };
});