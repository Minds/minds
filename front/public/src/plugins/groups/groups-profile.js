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
var GroupsProfile = (function () {
    function GroupsProfile(client, params) {
        this.client = client;
        this.params = params;
        this.offset = "";
        this.session = session_1.SessionFactory.build();
        this.guid = this.params.guid;
        this.load();
    }
    GroupsProfile.prototype.load = function () {
        var self = this;
        this.client.get('api/v1/groups/group/' + this.guid, {})
            .then(function (response) {
            self.group = response.group;
        })
            .catch(function (e) {
        });
    };
    GroupsProfile = __decorate([
        angular2_1.Component({
            selector: 'minds-groups',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/groups/profile.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink]
        }),
        __param(1, angular2_1.Inject(router_1.RouteParams)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.RouteParams])
    ], GroupsProfile);
    return GroupsProfile;
})();
exports.GroupsProfile = GroupsProfile;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMtcHJvZmlsZS50cyJdLCJuYW1lcyI6WyJHcm91cHNQcm9maWxlIiwiR3JvdXBzUHJvZmlsZS5jb25zdHJ1Y3RvciIsIkdyb3Vwc1Byb2ZpbGUubG9hZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBMEYsbUJBQW1CLENBQUMsQ0FBQTtBQUM5Ryx1QkFBd0MsaUJBQWlCLENBQUMsQ0FBQTtBQUUxRCxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWdCQ0EsdUJBQW1CQSxNQUFjQSxFQUNGQSxNQUFtQkE7UUFEL0JDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ0ZBLFdBQU1BLEdBQU5BLE1BQU1BLENBQWFBO1FBSmpEQSxXQUFNQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUNyQkEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBSzdCQSxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxJQUFJQSxDQUFDQTtRQUM3QkEsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsQ0FBQ0E7SUFDakJBLENBQUNBO0lBRUFELDRCQUFJQSxHQUFKQTtRQUNFRSxJQUFJQSxJQUFJQSxHQUFHQSxJQUFJQSxDQUFDQTtRQUNoQkEsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsR0FBR0EsQ0FBQ0Esc0JBQXNCQSxHQUFHQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxFQUFFQSxDQUFDQTthQUNwREEsSUFBSUEsQ0FBQ0EsVUFBQ0EsUUFBUUE7WUFDWEEsSUFBSUEsQ0FBQ0EsS0FBS0EsR0FBR0EsUUFBUUEsQ0FBQ0EsS0FBS0EsQ0FBQ0E7UUFDaENBLENBQUNBLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQUNBLENBQUNBO1FBRVRBLENBQUNBLENBQUNBLENBQUNBO0lBQ1BBLENBQUNBO0lBaENIRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsY0FBY0E7WUFDeEJBLFlBQVlBLEVBQUVBLENBQUVBLFlBQU1BLENBQUVBO1NBQ3pCQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSx1Q0FBdUNBO1lBQ3BEQSxVQUFVQSxFQUFFQSxDQUFFQSxnQkFBS0EsRUFBRUEsZUFBSUEsRUFBRUEsa0JBQU9BLEVBQUVBLG1CQUFRQSxFQUFFQSxtQkFBVUEsQ0FBRUE7U0FDM0RBLENBQUNBO1FBVUVBLFdBQUNBLGlCQUFNQSxDQUFDQSxvQkFBV0EsQ0FBQ0EsQ0FBQUE7O3NCQWlCdkJBO0lBQURBLG9CQUFDQTtBQUFEQSxDQWxDQSxBQWtDQ0EsSUFBQTtBQXpCWSxxQkFBYSxnQkF5QnpCLENBQUEiLCJmaWxlIjoic3JjL3BsdWdpbnMvZ3JvdXBzL2dyb3Vwcy1wcm9maWxlLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgTmdDbGFzcywgT2JzZXJ2YWJsZSwgSW5qZWN0LCBGT1JNX0RJUkVDVElWRVN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmssIFJvdXRlUGFyYW1zIH0gZnJvbSBcImFuZ3VsYXIyL3JvdXRlclwiO1xuXG5pbXBvcnQgeyBDbGllbnQgfSBmcm9tICdzcmMvc2VydmljZXMvYXBpJztcbmltcG9ydCB7IFNlc3Npb25GYWN0b3J5IH0gZnJvbSAnc3JjL3NlcnZpY2VzL3Nlc3Npb24nO1xuaW1wb3J0IHsgTWF0ZXJpYWwgfSBmcm9tICdzcmMvZGlyZWN0aXZlcy9tYXRlcmlhbCc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWdyb3VwcycsXG4gIHZpZXdCaW5kaW5nczogWyBDbGllbnQgXVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICd0ZW1wbGF0ZXMvcGx1Z2lucy9ncm91cHMvcHJvZmlsZS5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBOZ0ZvciwgTmdJZiwgTmdDbGFzcywgTWF0ZXJpYWwsIFJvdXRlckxpbmsgXVxufSlcblxuZXhwb3J0IGNsYXNzIEdyb3Vwc1Byb2ZpbGUge1xuXG4gIGd1aWQ7XG4gIGdyb3VwO1xuICBvZmZzZXQgOiBzdHJpbmcgPSBcIlwiO1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQsXG4gICAgQEluamVjdChSb3V0ZVBhcmFtcykgcHVibGljIHBhcmFtczogUm91dGVQYXJhbXNcbiAgICApe1xuICAgICAgdGhpcy5ndWlkID0gdGhpcy5wYXJhbXMuZ3VpZDtcbiAgICAgIHRoaXMubG9hZCgpO1xuXHR9XG5cbiAgbG9hZCgpe1xuICAgIHZhciBzZWxmID0gdGhpcztcbiAgICB0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS9ncm91cHMvZ3JvdXAvJyArIHRoaXMuZ3VpZCwge30pXG4gICAgICAudGhlbigocmVzcG9uc2UpID0+IHtcbiAgICAgICAgICBzZWxmLmdyb3VwID0gcmVzcG9uc2UuZ3JvdXA7XG4gICAgICB9KVxuICAgICAgLmNhdGNoKChlKT0+e1xuXG4gICAgICB9KTtcbiAgfVxuXG59XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=