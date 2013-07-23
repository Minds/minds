/**
 * kaltura-embed directive.
 */
angular.module('kaltura-embed', []).directive('kalturaPlayer', function () {
    return {
        restrict: "A,E",
        replace: false,
        transclude: false,
        compile: function (element, attrs) {

            // Generate new div id for the player injection.
            var generatedDivId = attrs.id + '_kaltura';

            // Add a new div with an id which is based on the parent's id.
            var playerDiv = '<div id="' + generatedDivId + '">' + '</div>';
            jQuery(element).html(playerDiv);

            // We return the linking function.
            return function ($scope, element, attrs, controller) {

                // Get the config object as indicated in the attributes from the scope.
                var configObject = $scope[attrs.kalturaOptions];

                // Set the mwEmbed configuration.
                for (var name in $scope.config.mw)
                {
                    if (configObject.mw.hasOwnProperty(name))
                    {
                        mw.setConfig(name, configObject.mw[name]);
                    }
                }

                // Set the target id attribute using the containing element id.
                configObject.widget.targetId = generatedDivId;

                // Embed the kWidget.
                kWidget.embed(configObject.widget);
            };

        }
    };
});
