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
        this.client.get('api/v1/groups/group/' + this.guid, {})
            .then(function (response) {
            console.log(response);
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMtcHJvZmlsZS50cyJdLCJuYW1lcyI6WyJHcm91cHNQcm9maWxlIiwiR3JvdXBzUHJvZmlsZS5jb25zdHJ1Y3RvciIsIkdyb3Vwc1Byb2ZpbGUubG9hZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBMEYsbUJBQW1CLENBQUMsQ0FBQTtBQUM5Ryx1QkFBd0MsaUJBQWlCLENBQUMsQ0FBQTtBQUUxRCxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWVDQSx1QkFBbUJBLE1BQWNBLEVBQ0ZBLE1BQW1CQTtRQUQvQkMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBUUE7UUFDRkEsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBYUE7UUFKakRBLFdBQU1BLEdBQVlBLEVBQUVBLENBQUNBO1FBQ3JCQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFLN0JBLElBQUlBLENBQUNBLElBQUlBLEdBQUdBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBO1FBQzdCQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxDQUFDQTtJQUNqQkEsQ0FBQ0E7SUFFQUQsNEJBQUlBLEdBQUpBO1FBQ0VFLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLEdBQUdBLENBQUNBLHNCQUFzQkEsR0FBR0EsSUFBSUEsQ0FBQ0EsSUFBSUEsRUFBRUEsRUFBRUEsQ0FBQ0E7YUFDcERBLElBQUlBLENBQUNBLFVBQUNBLFFBQVFBO1lBQ1hBLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLFFBQVFBLENBQUNBLENBQUNBO1FBQzFCQSxDQUFDQSxDQUFDQTthQUNEQSxLQUFLQSxDQUFDQSxVQUFDQSxDQUFDQTtRQUVUQSxDQUFDQSxDQUFDQSxDQUFDQTtJQUNQQSxDQUFDQTtJQTlCSEY7UUFBQ0Esb0JBQVNBLENBQUNBO1lBQ1RBLFFBQVFBLEVBQUVBLGNBQWNBO1lBQ3hCQSxZQUFZQSxFQUFFQSxDQUFFQSxZQUFNQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsV0FBV0EsRUFBRUEsdUNBQXVDQTtZQUNwREEsVUFBVUEsRUFBRUEsQ0FBRUEsZ0JBQUtBLEVBQUVBLGVBQUlBLEVBQUVBLGtCQUFPQSxFQUFFQSxtQkFBUUEsRUFBRUEsbUJBQVVBLENBQUVBO1NBQzNEQSxDQUFDQTtRQVNFQSxXQUFDQSxpQkFBTUEsQ0FBQ0Esb0JBQVdBLENBQUNBLENBQUFBOztzQkFnQnZCQTtJQUFEQSxvQkFBQ0E7QUFBREEsQ0FoQ0EsQUFnQ0NBLElBQUE7QUF2QlkscUJBQWEsZ0JBdUJ6QixDQUFBIiwiZmlsZSI6InNyYy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMtcHJvZmlsZS5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IENvbXBvbmVudCwgVmlldywgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE9ic2VydmFibGUsIEluamVjdCwgRk9STV9ESVJFQ1RJVkVTfSBmcm9tICdhbmd1bGFyMi9hbmd1bGFyMic7XG5pbXBvcnQgeyBSb3V0ZXJMaW5rLCBSb3V0ZVBhcmFtcyB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcblxuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1ncm91cHMnLFxuICB2aWV3QmluZGluZ3M6IFsgQ2xpZW50IF1cbn0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAndGVtcGxhdGVzL3BsdWdpbnMvZ3JvdXBzL3Byb2ZpbGUuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rIF1cbn0pXG5cbmV4cG9ydCBjbGFzcyBHcm91cHNQcm9maWxlIHtcblxuICBndWlkO1xuICBvZmZzZXQgOiBzdHJpbmcgPSBcIlwiO1xuICBzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50OiBDbGllbnQsXG4gICAgQEluamVjdChSb3V0ZVBhcmFtcykgcHVibGljIHBhcmFtczogUm91dGVQYXJhbXNcbiAgICApe1xuICAgICAgdGhpcy5ndWlkID0gdGhpcy5wYXJhbXMuZ3VpZDtcbiAgICAgIHRoaXMubG9hZCgpO1xuXHR9XG5cbiAgbG9hZCgpe1xuICAgIHRoaXMuY2xpZW50LmdldCgnYXBpL3YxL2dyb3Vwcy9ncm91cC8nICsgdGhpcy5ndWlkLCB7fSlcbiAgICAgIC50aGVuKChyZXNwb25zZSkgPT4ge1xuICAgICAgICAgIGNvbnNvbGUubG9nKHJlc3BvbnNlKTtcbiAgICAgIH0pXG4gICAgICAuY2F0Y2goKGUpPT57XG5cbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==