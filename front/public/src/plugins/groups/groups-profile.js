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
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var material_1 = require('src/directives/material');
var GroupsProfile = (function () {
    function GroupsProfile(client) {
        this.client = client;
        this.offset = "";
        this.session = session_1.SessionFactory.build();
    }
    GroupsProfile.prototype.load = function () {
        this.client.get('api/v1/groups/' + this.page, { limit: 12, offset: this.offset })
            .then(function (response) {
        })
            .catch(function (e) {
        });
    };
    GroupsProfile = __decorate([
        angular2_1.Component({
            selector: 'minds-groups',
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/groups/profile.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [api_1.Client])
    ], GroupsProfile);
    return GroupsProfile;
})();
exports.GroupsProfile = GroupsProfile;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMtcHJvZmlsZS50cyJdLCJuYW1lcyI6WyJHcm91cHNQcm9maWxlIiwiR3JvdXBzUHJvZmlsZS5jb25zdHJ1Y3RvciIsIkdyb3Vwc1Byb2ZpbGUubG9hZCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7QUFBQSx5QkFBa0YsbUJBQW1CLENBQUMsQ0FBQTtBQUN0Ryx1QkFBMkIsaUJBQWlCLENBQUMsQ0FBQTtBQUU3QyxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWNDQSx1QkFBbUJBLE1BQWNBO1FBQWRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBSGhDQSxXQUFNQSxHQUFZQSxFQUFFQSxDQUFDQTtRQUNyQkEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO0lBR2xDQSxDQUFDQTtJQUVBRCw0QkFBSUEsR0FBSkE7UUFDRUUsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsR0FBR0EsQ0FBQ0EsZ0JBQWdCQSxHQUFHQSxJQUFJQSxDQUFDQSxJQUFJQSxFQUFFQSxFQUFFQSxLQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxNQUFNQSxFQUFFQSxJQUFJQSxDQUFDQSxNQUFNQSxFQUFDQSxDQUFDQTthQUM3RUEsSUFBSUEsQ0FBQ0EsVUFBQ0EsUUFBUUE7UUFFZkEsQ0FBQ0EsQ0FBQ0E7YUFDREEsS0FBS0EsQ0FBQ0EsVUFBQ0EsQ0FBQ0E7UUFFVEEsQ0FBQ0EsQ0FBQ0EsQ0FBQ0E7SUFDUEEsQ0FBQ0E7SUF6QkhGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxjQUFjQTtZQUN4QkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHVDQUF1Q0E7WUFDcERBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxrQkFBT0EsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxDQUFFQTtTQUMzREEsQ0FBQ0E7O3NCQW9CREE7SUFBREEsb0JBQUNBO0FBQURBLENBM0JBLEFBMkJDQSxJQUFBO0FBbEJZLHFCQUFhLGdCQWtCekIsQ0FBQSIsImZpbGUiOiJzcmMvcGx1Z2lucy9ncm91cHMvZ3JvdXBzLXByb2ZpbGUuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBPYnNlcnZhYmxlLCBGT1JNX0RJUkVDVElWRVN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlckxpbmsgfSBmcm9tIFwiYW5ndWxhcjIvcm91dGVyXCI7XG5cbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtZ3JvdXBzJyxcbiAgdmlld0luamVjdG9yOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9wbHVnaW5zL2dyb3Vwcy9wcm9maWxlLmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbIE5nRm9yLCBOZ0lmLCBOZ0NsYXNzLCBNYXRlcmlhbCwgUm91dGVyTGluayBdXG59KVxuXG5leHBvcnQgY2xhc3MgR3JvdXBzUHJvZmlsZSB7XG5cbiAgb2Zmc2V0IDogc3RyaW5nID0gXCJcIjtcbiAgc2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudDogQ2xpZW50KXtcblx0fVxuXG4gIGxvYWQoKXtcbiAgICB0aGlzLmNsaWVudC5nZXQoJ2FwaS92MS9ncm91cHMvJyArIHRoaXMucGFnZSwgeyBsaW1pdDogMTIsIG9mZnNldDogdGhpcy5vZmZzZXR9KVxuICAgICAgLnRoZW4oKHJlc3BvbnNlKSA9PiB7XG5cbiAgICAgIH0pXG4gICAgICAuY2F0Y2goKGUpPT57XG5cbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==