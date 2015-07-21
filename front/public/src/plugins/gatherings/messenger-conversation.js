if (typeof __decorate !== "function") __decorate = function (decorators, target, key, desc) {
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") return Reflect.decorate(decorators, target, key, desc);
    switch (arguments.length) {
        case 2: return decorators.reduceRight(function(o, d) { return (d && d(o)) || o; }, target);
        case 3: return decorators.reduceRight(function(o, d) { return (d && d(target, key)), void 0; }, void 0);
        case 4: return decorators.reduceRight(function(o, d) { return (d && d(target, key, o)) || o; }, desc);
    }
};
if (typeof __metadata !== "function") __metadata = function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var angular2_1 = require('angular2/angular2');
var router_1 = require("angular2/router");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var MessengerConversation = (function () {
    function MessengerConversation(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
    }
    MessengerConversation = __decorate([
        angular2_1.Component({
            selector: 'minds-messenger-conversation',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/gatherings/gatherings.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.CSSClass, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], MessengerConversation);
    return MessengerConversation;
})();
exports.MessengerConversation = MessengerConversation;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dhdGhlcmluZ3MvbWVzc2VuZ2VyLWNvbnZlcnNhdGlvbi50cyJdLCJuYW1lcyI6WyJNZXNzZW5nZXJDb252ZXJzYXRpb24iLCJNZXNzZW5nZXJDb252ZXJzYXRpb24uY29uc3RydWN0b3IiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQUEseUJBQWtGLG1CQUFtQixDQUFDLENBQUE7QUFDdEcsdUJBQTJCLGlCQUFpQixDQUFDLENBQUE7QUFDN0Msb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFDdEQseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFFbkQ7SUFhQ0EsK0JBQW1CQSxNQUFjQTtRQUFkQyxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQUZoQ0EsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO0lBR2xDQSxDQUFDQTtJQWRGRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsOEJBQThCQTtZQUN4Q0EsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLDhDQUE4Q0E7WUFDM0RBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUMzREEsQ0FBQ0E7OzhCQVNEQTtJQUFEQSw0QkFBQ0E7QUFBREEsQ0FoQkEsSUFnQkM7QUFQWSw2QkFBcUIsd0JBT2pDLENBQUEiLCJmaWxlIjoic3JjL3BsdWdpbnMvZ2F0aGVyaW5ncy9tZXNzZW5nZXItY29udmVyc2F0aW9uLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgQ1NTQ2xhc3MsIE9ic2VydmFibGUsIGZvcm1EaXJlY3RpdmVzfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1tZXNzZW5nZXItY29udmVyc2F0aW9uJyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9wbHVnaW5zL2dhdGhlcmluZ3MvZ2F0aGVyaW5ncy5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgQ1NTQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rXVxufSlcblxuZXhwb3J0IGNsYXNzIE1lc3NlbmdlckNvbnZlcnNhdGlvbiB7XG4gIGFjdGl2aXR5IDogYW55O1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQpe1xuXHR9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==