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
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
var angular2_1 = require('angular2/angular2');
var router_1 = require("angular2/router");
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var Groups = (function () {
    function Groups(client, router, params) {
        this.client = client;
        this.router = router;
        this.params = params;
        this.offset = "";
        this.session = session_1.SessionFactory.build();
        this._filter = "featured";
        this._filter = params.params['filter'];
    }
    Groups.prototype.load = function () {
        this.client.get('api/v1/groups/' + this.page, { limit: 12, offset: this.offset })
            .then(function (response) {
        })
            .catch(function (e) {
        });
    };
    Groups = __decorate([
        angular2_1.Component({
            selector: 'minds-groups',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/groups/groups.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink]
        }),
        __param(1, angular2_1.Inject(router_1.Router)),
        __param(2, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router, router_1.RouteParams])
    ], Groups);
    return Groups;
})();
exports.Groups = Groups;
var groups_profile_1 = require('./groups-profile');
exports.GroupsProfile = groups_profile_1.GroupsProfile;
var groups_creator_1 = require('./groups-creator');
exports.GroupsCreator = groups_creator_1.GroupsCreator;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMudHMiXSwibmFtZXMiOlsiR3JvdXBzIiwiR3JvdXBzLmNvbnN0cnVjdG9yIiwiR3JvdXBzLmxvYWQiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQUEseUJBQTBGLG1CQUFtQixDQUFDLENBQUE7QUFDOUcsdUJBQWdELGlCQUFpQixDQUFDLENBQUE7QUFFbEUsb0JBQXVCLGtCQUFrQixDQUFDLENBQUE7QUFDMUMsd0JBQStCLHNCQUFzQixDQUFDLENBQUE7QUFDdEQseUJBQXlCLHlCQUF5QixDQUFDLENBQUE7QUFJbkQ7SUFlRUEsZ0JBQW1CQSxNQUFjQSxFQUNSQSxNQUFjQSxFQUNUQSxNQUFtQkE7UUFGOUJDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ1JBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ1RBLFdBQU1BLEdBQU5BLE1BQU1BLENBQWFBO1FBTmpEQSxXQUFNQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUNyQkEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBQ2pDQSxZQUFPQSxHQUFZQSxVQUFVQSxDQUFDQTtRQU0xQkEsSUFBSUEsQ0FBQ0EsT0FBT0EsR0FBR0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsQ0FBQ0E7SUFDM0NBLENBQUNBO0lBRURELHFCQUFJQSxHQUFKQTtRQUNFRSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxHQUFHQSxDQUFDQSxnQkFBZ0JBLEdBQUdBLElBQUlBLENBQUNBLElBQUlBLEVBQUVBLEVBQUVBLEtBQUtBLEVBQUVBLEVBQUVBLEVBQUVBLE1BQU1BLEVBQUVBLElBQUlBLENBQUNBLE1BQU1BLEVBQUNBLENBQUNBO2FBQzdFQSxJQUFJQSxDQUFDQSxVQUFDQSxRQUFRQTtRQUVmQSxDQUFDQSxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFDQSxDQUFDQTtRQUVUQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNQQSxDQUFDQTtJQTlCSEY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsc0NBQXNDQTtZQUNuREEsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLGtCQUFPQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVVBLENBQUVBO1NBQzNEQSxDQUFDQTtRQVNFQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7UUFDZkEsV0FBQ0EsaUJBQU1BLENBQUNBLG9CQUFXQSxDQUFDQSxDQUFBQTs7ZUFldkJBO0lBQURBLGFBQUNBO0FBQURBLENBaENBLEFBZ0NDQSxJQUFBO0FBdkJZLGNBQU0sU0F1QmxCLENBQUE7QUFFRCwrQkFBOEIsa0JBQWtCLENBQUM7QUFBeEMsdURBQXdDO0FBQ2pELCtCQUE4QixrQkFBa0IsQ0FBQztBQUF4Qyx1REFBd0MiLCJmaWxlIjoic3JjL3BsdWdpbnMvZ3JvdXBzL2dyb3Vwcy5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE9ic2VydmFibGUsIEluamVjdCwgRk9STV9ESVJFQ1RJVkVTfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rLCBSb3V0ZXIsIFJvdXRlUGFyYW1zIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuXG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnc3JjL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5cbmltcG9ydCB7IEdyb3Vwc0NyZWF0b3IgfSBmcm9tICcuL2dyb3Vwcy1jcmVhdG9yJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtZ3JvdXBzJyxcbiAgdmlld0JpbmRpbmdzOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBHcm91cHMge1xuXG4gIG9mZnNldCA6IHN0cmluZyA9IFwiXCI7XG4gIHNlc3Npb24gPSBTZXNzaW9uRmFjdG9yeS5idWlsZCgpO1xuICBfZmlsdGVyIDogc3RyaW5nID0gXCJmZWF0dXJlZFwiO1xuXG4gIGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCxcbiAgICBASW5qZWN0KFJvdXRlcikgcHVibGljIHJvdXRlcjogUm91dGVyLFxuICAgIEBJbmplY3QoUm91dGVQYXJhbXMpIHB1YmxpYyBwYXJhbXM6IFJvdXRlUGFyYW1zXG4gICAgKXtcbiAgICAgIHRoaXMuX2ZpbHRlciA9IHBhcmFtcy5wYXJhbXNbJ2ZpbHRlciddO1xuICB9XG5cbiAgbG9hZCgpe1xuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL2dyb3Vwcy8nICsgdGhpcy5wYWdlLCB7IGxpbWl0OiAxMiwgb2Zmc2V0OiB0aGlzLm9mZnNldH0pXG4gICAgICAudGhlbigocmVzcG9uc2UpID0+IHtcblxuICAgICAgfSlcbiAgICAgIC5jYXRjaCgoZSk9PntcblxuICAgICAgfSk7XG4gIH1cblxufVxuXG5leHBvcnQgeyBHcm91cHNQcm9maWxlIH0gZnJvbSAnLi9ncm91cHMtcHJvZmlsZSc7XG5leHBvcnQgeyBHcm91cHNDcmVhdG9yIH0gZnJvbSAnLi9ncm91cHMtY3JlYXRvcic7XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=