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
var GroupsCreator = (function () {
    function GroupsCreator(client, router) {
        this.client = client;
        this.router = router;
        this.session = session_1.SessionFactory.build();
        this.group = {
            name: '',
            description: '',
            membership: 2
        };
    }
    GroupsCreator.prototype.membershipChange = function (value) {
        console.log(value);
        this.group.membership = value;
        this.group.foo = 'bar';
    };
    GroupsCreator.prototype.save = function () {
        console.log(this.group);
        var self = this;
        this.client.post('api/v1/groups/group', this.group)
            .then(function (response) {
        })
            .catch(function (e) {
        });
    };
    GroupsCreator = __decorate([
        angular2_1.Component({
            selector: 'minds-groups-create',
            viewBindings: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/plugins/groups/create.html',
            directives: [angular2_1.NgFor, angular2_1.NgIf, angular2_1.NgClass, material_1.Material, router_1.RouterLink, angular2_1.FORM_DIRECTIVES]
        }),
        __param(1, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router])
    ], GroupsCreator);
    return GroupsCreator;
})();
exports.GroupsCreator = GroupsCreator;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9wbHVnaW5zL2dyb3Vwcy9ncm91cHMtY3JlYXRvci50cyJdLCJuYW1lcyI6WyJHcm91cHNDcmVhdG9yIiwiR3JvdXBzQ3JlYXRvci5jb25zdHJ1Y3RvciIsIkdyb3Vwc0NyZWF0b3IubWVtYmVyc2hpcENoYW5nZSIsIkdyb3Vwc0NyZWF0b3Iuc2F2ZSJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBMEYsbUJBQW1CLENBQUMsQ0FBQTtBQUM5Ryx1QkFBbUMsaUJBQWlCLENBQUMsQ0FBQTtBQUVyRCxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUN0RCx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUVuRDtJQWtCRUEsdUJBQW1CQSxNQUFjQSxFQUF5QkEsTUFBY0E7UUFBckRDLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQXlCQSxXQUFNQSxHQUFOQSxNQUFNQSxDQUFRQTtRQVB4RUEsWUFBT0EsR0FBR0Esd0JBQWNBLENBQUNBLEtBQUtBLEVBQUVBLENBQUNBO1FBQ2pDQSxVQUFLQSxHQUFHQTtZQUNOQSxJQUFJQSxFQUFFQSxFQUFFQTtZQUNSQSxXQUFXQSxFQUFFQSxFQUFFQTtZQUNmQSxVQUFVQSxFQUFFQSxDQUFDQTtTQUNkQSxDQUFDQTtJQUlGQSxDQUFDQTtJQUVERCx3Q0FBZ0JBLEdBQWhCQSxVQUFpQkEsS0FBS0E7UUFDcEJFLE9BQU9BLENBQUNBLEdBQUdBLENBQUNBLEtBQUtBLENBQUNBLENBQUNBO1FBQ25CQSxJQUFJQSxDQUFDQSxLQUFLQSxDQUFDQSxVQUFVQSxHQUFHQSxLQUFLQSxDQUFDQTtRQUM5QkEsSUFBSUEsQ0FBQ0EsS0FBS0EsQ0FBQ0EsR0FBR0EsR0FBR0EsS0FBS0EsQ0FBQ0E7SUFDekJBLENBQUNBO0lBRURGLDRCQUFJQSxHQUFKQTtRQUNFRyxPQUFPQSxDQUFDQSxHQUFHQSxDQUFDQSxJQUFJQSxDQUFDQSxLQUFLQSxDQUFDQSxDQUFDQTtRQUV4QkEsSUFBSUEsSUFBSUEsR0FBR0EsSUFBSUEsQ0FBQ0E7UUFDaEJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLElBQUlBLENBQUNBLHFCQUFxQkEsRUFBRUEsSUFBSUEsQ0FBQ0EsS0FBS0EsQ0FBQ0E7YUFDaERBLElBQUlBLENBQUNBLFVBQUNBLFFBQVFBO1FBRWZBLENBQUNBLENBQUNBO2FBQ0RBLEtBQUtBLENBQUNBLFVBQUNBLENBQUNBO1FBRVRBLENBQUNBLENBQUNBLENBQUNBO0lBQ1BBLENBQUNBO0lBdkNISDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEscUJBQXFCQTtZQUMvQkEsWUFBWUEsRUFBRUEsQ0FBRUEsWUFBTUEsQ0FBRUE7U0FDekJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHNDQUFzQ0E7WUFDbkRBLFVBQVVBLEVBQUVBLENBQUVBLGdCQUFLQSxFQUFFQSxlQUFJQSxFQUFFQSxrQkFBT0EsRUFBRUEsbUJBQVFBLEVBQUVBLG1CQUFVQSxFQUFFQSwwQkFBZUEsQ0FBRUE7U0FDNUVBLENBQUNBO1FBV21DQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7O3NCQXVCbkRBO0lBQURBLG9CQUFDQTtBQUFEQSxDQXpDQSxBQXlDQ0EsSUFBQTtBQWhDWSxxQkFBYSxnQkFnQ3pCLENBQUEiLCJmaWxlIjoic3JjL3BsdWdpbnMvZ3JvdXBzL2dyb3Vwcy1jcmVhdG9yLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgQ29tcG9uZW50LCBWaWV3LCBOZ0ZvciwgTmdJZiwgTmdDbGFzcywgT2JzZXJ2YWJsZSwgSW5qZWN0LCBGT1JNX0RJUkVDVElWRVN9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlciwgUm91dGVyTGluayB9IGZyb20gXCJhbmd1bGFyMi9yb3V0ZXJcIjtcblxuaW1wb3J0IHsgQ2xpZW50IH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQgeyBTZXNzaW9uRmFjdG9yeSB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9zZXNzaW9uJztcbmltcG9ydCB7IE1hdGVyaWFsIH0gZnJvbSAnc3JjL2RpcmVjdGl2ZXMvbWF0ZXJpYWwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1ncm91cHMtY3JlYXRlJyxcbiAgdmlld0JpbmRpbmdzOiBbIENsaWVudCBdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9wbHVnaW5zL2dyb3Vwcy9jcmVhdGUuaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFsgTmdGb3IsIE5nSWYsIE5nQ2xhc3MsIE1hdGVyaWFsLCBSb3V0ZXJMaW5rLCBGT1JNX0RJUkVDVElWRVMgXVxufSlcblxuZXhwb3J0IGNsYXNzIEdyb3Vwc0NyZWF0b3Ige1xuXG4gIHNlc3Npb24gPSBTZXNzaW9uRmFjdG9yeS5idWlsZCgpO1xuICBncm91cCA9IHtcbiAgICBuYW1lOiAnJyxcbiAgICBkZXNjcmlwdGlvbjogJycsXG4gICAgbWVtYmVyc2hpcDogMlxuICB9O1xuXG4gIGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQ6IENsaWVudCwgQEluamVjdChSb3V0ZXIpIHB1YmxpYyByb3V0ZXI6IFJvdXRlcil7XG5cbiAgfVxuXG4gIG1lbWJlcnNoaXBDaGFuZ2UodmFsdWUpe1xuICAgIGNvbnNvbGUubG9nKHZhbHVlKTtcbiAgICB0aGlzLmdyb3VwLm1lbWJlcnNoaXAgPSB2YWx1ZTtcbiAgICB0aGlzLmdyb3VwLmZvbyA9ICdiYXInO1xuICB9XG5cbiAgc2F2ZSgpe1xuICAgIGNvbnNvbGUubG9nKHRoaXMuZ3JvdXApO1xuXG4gICAgdmFyIHNlbGYgPSB0aGlzO1xuICAgIHRoaXMuY2xpZW50LnBvc3QoJ2FwaS92MS9ncm91cHMvZ3JvdXAnLCB0aGlzLmdyb3VwKVxuICAgICAgLnRoZW4oKHJlc3BvbnNlKSA9PiB7XG5cbiAgICAgIH0pXG4gICAgICAuY2F0Y2goKGUpPT57XG5cbiAgICAgIH0pO1xuICB9XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==