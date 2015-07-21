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
var material_1 = require('src/directives/material');
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
            templateUrl: 'templates/login.html',
            directives: [material_1.Material]
        }),
        __param(1, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router])
    ], Logout);
    return Logout;
})();
exports.Logout = Logout;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dvdXQudHMiXSwibmFtZXMiOlsiTG9nb3V0IiwiTG9nb3V0LmNvbnN0cnVjdG9yIiwiTG9nb3V0LmxvZ291dCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBd0MsbUJBQW1CLENBQUMsQ0FBQTtBQUM1RCx1QkFBdUIsaUJBQWlCLENBQUMsQ0FBQTtBQUN6Qyx5QkFBeUIseUJBQXlCLENBQUMsQ0FBQTtBQUNuRCxvQkFBdUIsa0JBQWtCLENBQUMsQ0FBQTtBQUMxQyx3QkFBK0Isc0JBQXNCLENBQUMsQ0FBQTtBQUV0RDtJQVlDQSxnQkFBbUJBLE1BQWVBLEVBQXlCQSxNQUFjQTtRQUF0REMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBU0E7UUFBeUJBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBRnpFQSxZQUFPQSxHQUFHQSx3QkFBY0EsQ0FBQ0EsS0FBS0EsRUFBRUEsQ0FBQ0E7UUFHaENBLElBQUlBLENBQUNBLE1BQU1BLEVBQUVBLENBQUNBO0lBQ2ZBLENBQUNBO0lBRURELHVCQUFNQSxHQUFOQTtRQUNDRSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN0Q0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EscUJBQXFCQSxDQUFDQSxDQUFDQTtRQUMxQ0EsSUFBSUEsQ0FBQ0EsT0FBT0EsQ0FBQ0EsTUFBTUEsRUFBRUEsQ0FBQ0E7SUFDdkJBLENBQUNBO0lBcEJGRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsWUFBWUEsRUFBRUEsQ0FBQ0EsWUFBTUEsQ0FBQ0E7U0FDdkJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHNCQUFzQkE7WUFDbkNBLFVBQVVBLEVBQUVBLENBQUVBLG1CQUFRQSxDQUFFQTtTQUN6QkEsQ0FBQ0E7UUFNbUNBLFdBQUNBLGlCQUFNQSxDQUFDQSxlQUFNQSxDQUFDQSxDQUFBQTs7ZUFTbkRBO0lBQURBLGFBQUNBO0FBQURBLENBckJBLElBcUJDO0FBYlksY0FBTSxTQWFsQixDQUFBIiwiZmlsZSI6InNyYy9jb250cm9sbGVycy9sb2dvdXQuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyBDb21wb25lbnQsIFZpZXcsIEluamVjdCB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7IFJvdXRlciB9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQgeyBNYXRlcmlhbCB9IGZyb20gJ3NyYy9kaXJlY3RpdmVzL21hdGVyaWFsJztcbmltcG9ydCB7IENsaWVudCB9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuaW1wb3J0IHsgU2Vzc2lvbkZhY3RvcnkgfSBmcm9tICdzcmMvc2VydmljZXMvc2Vzc2lvbic7XG5cbkBDb21wb25lbnQoe1xuICB2aWV3SW5qZWN0b3I6IFtDbGllbnRdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJ3RlbXBsYXRlcy9sb2dpbi5odG1sJyxcbiAgZGlyZWN0aXZlczogWyBNYXRlcmlhbCBdXG59KVxuXG5leHBvcnQgY2xhc3MgTG9nb3V0IHtcblxuXHRzZXNzaW9uID0gU2Vzc2lvbkZhY3RvcnkuYnVpbGQoKTtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50IDogQ2xpZW50LCBASW5qZWN0KFJvdXRlcikgcHVibGljIHJvdXRlcjogUm91dGVyKXtcblx0XHR0aGlzLmxvZ291dCgpO1xuXHR9XG5cblx0bG9nb3V0KCl7XG5cdFx0dGhpcy5yb3V0ZXIucGFyZW50Lm5hdmlnYXRlKCcvbG9naW4nKTtcblx0XHR0aGlzLmNsaWVudC5kZWxldGUoJ2FwaS92MS9hdXRoZW50aWNhdGUnKTtcblx0XHR0aGlzLnNlc3Npb24ubG9nb3V0KCk7XG5cdH1cbn1cbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==