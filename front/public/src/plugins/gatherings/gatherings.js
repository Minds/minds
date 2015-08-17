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
var router_1 = require("angular2/router");
var messenger_conversation_1 = require("./messenger-conversation");
var messenger_setup_1 = require("./messenger-setup");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var Gatherings = (function () {
    function Gatherings(client) {
        this.client = client;
        this.session = session_1.SessionFactory.build();
        this.setup = false;
    }
    Gatherings = __decorate([
        angular2_1.Component({
            selector: 'minds-gatherings',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/gatherings/gatherings.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink, messenger_conversation_1.MessengerConversation, messenger_setup_1.MessengerSetup]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], Gatherings);
    return Gatherings;
})();
exports.Gatherings = Gatherings;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dhdGhlcmluZ3MvZ2F0aGVyaW5ncy50cyJdLCJuYW1lcyI6WyJHYXRoZXJpbmdzIiwiR2F0aGVyaW5ncy5jb25zdHJ1Y3RvciJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBa0YsbUJBQW1CLENBQUMsQ0FBQTtBQUN0Ryx1QkFBMkIsaUJBQWlCLENBQUMsQ0FBQTtBQUM3Qyx1Q0FBc0MsMEJBQTBCLENBQUMsQ0FBQTtBQUNqRSxnQ0FBK0IsbUJBQW1CLENBQUMsQ0FBQTtBQUVuRCxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWNDQSxvQkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBSGhDQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFDakNBLFVBQUtBLEdBQWFBLEtBQUtBLENBQUNBO0lBR3pCQSxDQUFDQTtJQWZGRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsa0JBQWtCQTtZQUM1QkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLDhDQUE4Q0E7WUFDM0RBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxrQkFBT0EsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxFQUFFQSw4Q0FBcUJBLEVBQUVBLGdDQUFjQSxDQUFDQTtTQUNqR0EsQ0FBQ0E7O21CQVVEQTtJQUFEQSxpQkFBQ0E7QUFBREEsQ0FqQkEsQUFpQkNBLElBQUE7QUFSWSxrQkFBVSxhQVF0QixDQUFBIiwiZmlsZSI6InNyYy9wbHVnaW5zL2dhdGhlcmluZ3MvZ2F0aGVyaW5ncy5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE9ic2VydmFibGUsIEZPUk1fRElSRUNUSVZFU30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHsgUm91dGVyTGluayB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcbmltcG9ydCB7IE1lc3NlbmdlckNvbnZlcnNhdGlvbiB9IGZyb20gXCIuL21lc3Nlbmdlci1jb252ZXJzYXRpb25cIjtcbmltcG9ydCB7IE1lc3NlbmdlclNldHVwIH0gZnJvbSBcIi4vbWVzc2VuZ2VyLXNldHVwXCI7XG5cbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtZ2F0aGVyaW5ncycsXG4gIHZpZXdJbmplY3RvcjogWyBDbGllbnQgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvcGx1Z2lucy9nYXRoZXJpbmdzL2dhdGhlcmluZ3MuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rLCBNZXNzZW5nZXJDb252ZXJzYXRpb24sIE1lc3NlbmdlclNldHVwXVxufSlcblxuZXhwb3J0IGNsYXNzIEdhdGhlcmluZ3Mge1xuICBhY3Rpdml0eSA6IGFueTtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG4gIHNldHVwIDogYm9vbGVhbiA9IGZhbHNlO1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCl7XG5cdH1cblxufVxuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9