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
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var MessengerSetup = (function () {
    function MessengerSetup(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
    }
    MessengerSetup.prototype.setup = function (passwords) {
        console.log(passwords);
        passwords.value = {};
        return true;
    };
    MessengerSetup = __decorate([
        angular2_1.Component({
            selector: 'minds-messenger-setup',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/gatherings/messenger-setup.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.CSSClass, material_1.Material, angular2_1.formDirectives]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], MessengerSetup);
    return MessengerSetup;
})();
exports.MessengerSetup = MessengerSetup;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dhdGhlcmluZ3MvbWVzc2VuZ2VyLXNldHVwLnRzIl0sIm5hbWVzIjpbIk1lc3NlbmdlclNldHVwIiwiTWVzc2VuZ2VyU2V0dXAuY29uc3RydWN0b3IiLCJNZXNzZW5nZXJTZXR1cC5zZXR1cCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBa0YsbUJBQW1CLENBQUMsQ0FBQTtBQUV0RyxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWFDQSx3QkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBRmhDQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7SUFHbENBLENBQUNBO0lBRUFELDhCQUFLQSxHQUFMQSxVQUFNQSxTQUFTQTtRQUNiRSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxTQUFTQSxDQUFDQSxDQUFDQTtRQUN2QkEsU0FBU0EsQ0FBQ0EsS0FBS0EsR0FBR0EsRUFBRUEsQ0FBQ0E7UUFDckJBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBO0lBQ2RBLENBQUNBO0lBcEJIRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsdUJBQXVCQTtZQUNqQ0EsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLG1EQUFtREE7WUFDaEVBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVFBLEVBQUVBLHlCQUFjQSxDQUFDQTtTQUMvREEsQ0FBQ0E7O3VCQWVEQTtJQUFEQSxxQkFBQ0E7QUFBREEsQ0F0QkEsSUFzQkM7QUFiWSxzQkFBYyxpQkFhMUIsQ0FBQSIsImZpbGUiOiJzcmMvcGx1Z2lucy9nYXRoZXJpbmdzL21lc3Nlbmdlci1zZXR1cC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIENTU0NsYXNzLCBPYnNlcnZhYmxlLCBmb3JtRGlyZWN0aXZlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtbWVzc2VuZ2VyLXNldHVwJyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9wbHVnaW5zL2dhdGhlcmluZ3MvbWVzc2VuZ2VyLXNldHVwLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBDU1NDbGFzcywgTWF0ZXJpYWwsIGZvcm1EaXJlY3RpdmVzXVxufSlcblxuZXhwb3J0IGNsYXNzIE1lc3NlbmdlclNldHVwIHtcbiAgYWN0aXZpdHkgOiBhbnk7XG4gIHNlc3Npb24gPSBTZXNzaW9uRmFjdG9yeS5idWlsZCgpO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7XG5cdH1cblxuICBzZXR1cChwYXNzd29yZHMpe1xuICAgIGNvbnNvbGUubG9nKHBhc3N3b3Jkcyk7XG4gICAgcGFzc3dvcmRzLnZhbHVlID0ge307XG4gICAgcmV0dXJuIHRydWU7XG4gIH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9