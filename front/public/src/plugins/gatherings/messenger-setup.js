var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") return Reflect.decorate(decorators, target, key, desc);
    switch (arguments.length) {
        case 2: return decorators.reduceRight(function(o, d) { return (d && d(o)) || o; }, target);
        case 3: return decorators.reduceRight(function(o, d) { return (d && d(target, key)), void 0; }, void 0);
        case 4: return decorators.reduceRight(function(o, d) { return (d && d(target, key, o)) || o; }, desc);
    }
};
var __metadata = (this && this.__metadata) || function (k, v) {
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
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/gatherings/messenger-setup.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, angular2_1.FORM_DIRECTIVES]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], MessengerSetup);
    return MessengerSetup;
})();
exports.MessengerSetup = MessengerSetup;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dhdGhlcmluZ3MvbWVzc2VuZ2VyLXNldHVwLnRzIl0sIm5hbWVzIjpbIk1lc3NlbmdlclNldHVwIiwiTWVzc2VuZ2VyU2V0dXAuY29uc3RydWN0b3IiLCJNZXNzZW5nZXJTZXR1cC5zZXR1cCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBa0YsbUJBQW1CLENBQUMsQ0FBQTtBQUV0RyxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQVlDQSx3QkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBRmhDQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7SUFHbENBLENBQUNBO0lBRUFELDhCQUFLQSxHQUFMQSxVQUFNQSxTQUFTQTtRQUNiRSxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxTQUFTQSxDQUFDQSxDQUFDQTtRQUN2QkEsU0FBU0EsQ0FBQ0EsS0FBS0EsR0FBR0EsRUFBRUEsQ0FBQ0E7UUFDckJBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBO0lBQ2RBLENBQUNBO0lBbkJIRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsdUJBQXVCQTtZQUNqQ0EsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLG1EQUFtREE7WUFDaEVBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxrQkFBT0EsRUFBRUEsbUJBQVFBLEVBQUVBLDBCQUFlQSxDQUFDQTtTQUMvREEsQ0FBQ0E7O3VCQWNEQTtJQUFEQSxxQkFBQ0E7QUFBREEsQ0FyQkEsQUFxQkNBLElBQUE7QUFaWSxzQkFBYyxpQkFZMUIsQ0FBQSIsImZpbGUiOiJzcmMvcGx1Z2lucy9nYXRoZXJpbmdzL21lc3Nlbmdlci1zZXR1cC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE9ic2VydmFibGUsIEZPUk1fRElSRUNUSVZFU30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtbWVzc2VuZ2VyLXNldHVwJyxcbiAgdmlld0JpbmRpbmdzOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9wbHVnaW5zL2dhdGhlcmluZ3MvbWVzc2VuZ2VyLXNldHVwLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBNYXRlcmlhbCwgRk9STV9ESVJFQ1RJVkVTXVxufSlcblxuZXhwb3J0IGNsYXNzIE1lc3NlbmdlclNldHVwIHtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50KXtcblx0fVxuXG4gIHNldHVwKHBhc3N3b3Jkcyl7XG4gICAgY29uc29sZS5sb2cocGFzc3dvcmRzKTtcbiAgICBwYXNzd29yZHMudmFsdWUgPSB7fTtcbiAgICByZXR1cm4gdHJ1ZTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=