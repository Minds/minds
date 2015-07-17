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
if (typeof __param !== "function") __param = function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
var api_1 = require('src/services/api');
var session_1 = require('src/services/session');
var Logout = (function () {
    function Logout(client, router) {
        this.client = client;
        this.router = router;
        this.session = session_1.SessionFactory.build();
        this.logout();
    }
    Logout.prototype.logout = function () {
        this.router.parent.navigate('/login');
        this.client.delete('api/v1/authenticate');
        this.session.logout();
    };
    Logout = __decorate([
        angular2_1.Component({
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            templateUrl: 'templates/login.html'
        }),
        __param(1, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router])
    ], Logout);
    return Logout;
})();
exports.Logout = Logout;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dvdXQudHMiXSwibmFtZXMiOlsiTG9nb3V0IiwiTG9nb3V0LmNvbnN0cnVjdG9yIiwiTG9nb3V0LmxvZ291dCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBc0MsbUJBQW1CLENBQUMsQ0FBQTtBQUMxRCx1QkFBcUIsaUJBQWlCLENBQUMsQ0FBQTtBQUN2QyxvQkFBcUIsa0JBQWtCLENBQUMsQ0FBQTtBQUN4Qyx3QkFBNkIsc0JBQXNCLENBQUMsQ0FBQTtBQUVwRDtJQVdDQSxnQkFBbUJBLE1BQWVBLEVBQXlCQSxNQUFjQTtRQUF0REMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBU0E7UUFBeUJBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBRnpFQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFHaENBLElBQUlBLENBQUNBLE1BQU1BLEVBQUVBLENBQUNBO0lBQ2ZBLENBQUNBO0lBRURELHVCQUFNQSxHQUFOQTtRQUNDRSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN0Q0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EscUJBQXFCQSxDQUFDQSxDQUFDQTtRQUMxQ0EsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsTUFBTUEsRUFBRUEsQ0FBQ0E7SUFDdkJBLENBQUNBO0lBbkJGRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsWUFBWUEsRUFBRUEsQ0FBQ0EsWUFBTUEsQ0FBQ0E7U0FDdkJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHNCQUFzQkE7U0FDcENBLENBQUNBO1FBTW1DQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7O2VBU25EQTtJQUFEQSxhQUFDQTtBQUFEQSxDQXBCQSxJQW9CQztBQWJZLGNBQU0sU0FhbEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbG9nb3V0LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXcsIEluamVjdH0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZXJ9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQge0NsaWVudH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5pbXBvcnQge1Nlc3Npb25GYWN0b3J5fSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5cbkBDb21wb25lbnQoe1xuICB2aWV3SW5qZWN0b3I6IFtDbGllbnRdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9sb2dpbi5odG1sJ1xufSlcblxuZXhwb3J0IGNsYXNzIExvZ291dCB7XG5cblx0c2Vzc2lvbiA9IFNlc3Npb25GYWN0b3J5LmJ1aWxkKCk7XG5cblx0Y29uc3RydWN0b3IocHVibGljIGNsaWVudCA6IENsaWVudCwgQEluamVjdChSb3V0ZXIpIHB1YmxpYyByb3V0ZXI6IFJvdXRlcil7XG5cdFx0dGhpcy5sb2dvdXQoKTtcblx0fVxuXG5cdGxvZ291dCgpe1xuXHRcdHRoaXMucm91dGVyLnBhcmVudC5uYXZpZ2F0ZSgnL2xvZ2luJyk7XG5cdFx0dGhpcy5jbGllbnQuZGVsZXRlKCdhcGkvdjEvYXV0aGVudGljYXRlJyk7XG5cdFx0dGhpcy5zZXNzaW9uLmxvZ291dCgpO1xuXHR9XG59Il0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9