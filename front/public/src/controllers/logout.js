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
var Logout = (function () {
    function Logout(client, router) {
        this.client = client;
        this.router = router;
        this.logout();
    }
    Logout.prototype.logout = function () {
        this.router.navigate('/login');
        window.LoggedIn = false;
        this.router.parent.navigate('/login');
        console.log(this.router);
        this.client.delete('api/v1/authenticate');
    };
    Logout = __decorate([
        angular2_1.Component({
            viewInjector: [api_1.Client]
        }),
        angular2_1.View({
            template: "Logging out.."
        }),
        __param(1, angular2_1.Inject(router_1.Router)), 
        __metadata('design:paramtypes', [api_1.Client, router_1.Router])
    ], Logout);
    return Logout;
})();
exports.Logout = Logout;

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dvdXQudHMiXSwibmFtZXMiOlsiTG9nb3V0IiwiTG9nb3V0LmNvbnN0cnVjdG9yIiwiTG9nb3V0LmxvZ291dCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBc0MsbUJBQW1CLENBQUMsQ0FBQTtBQUMxRCx1QkFBcUIsaUJBQWlCLENBQUMsQ0FBQTtBQUN2QyxvQkFBcUIsa0JBQWtCLENBQUMsQ0FBQTtBQUV4QztJQVNDQSxnQkFBbUJBLE1BQWVBLEVBQXlCQSxNQUFjQTtRQUF0REMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBU0E7UUFBeUJBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ3hFQSxJQUFJQSxDQUFDQSxNQUFNQSxFQUFFQSxDQUFDQTtJQUNmQSxDQUFDQTtJQUVERCx1QkFBTUEsR0FBTkE7UUFFQ0UsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsQ0FBQ0E7UUFDL0JBLE1BQU1BLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO1FBQ3hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN0Q0EsT0FBT0EsQ0FBQ0EsR0FBR0EsQ0FBQ0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsQ0FBQ0E7UUFDekJBLElBQUlBLENBQUNBLE1BQU1BLENBQUNBLE1BQU1BLENBQUNBLHFCQUFxQkEsQ0FBQ0EsQ0FBQ0E7SUFDM0NBLENBQUNBO0lBcEJGRjtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsWUFBWUEsRUFBRUEsQ0FBQ0EsWUFBTUEsQ0FBQ0E7U0FDdkJBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFFBQVFBLEVBQUVBLGVBQWVBO1NBQzFCQSxDQUFDQTtRQUltQ0EsV0FBQ0EsaUJBQU1BLENBQUNBLGVBQU1BLENBQUNBLENBQUFBOztlQVluREE7SUFBREEsYUFBQ0E7QUFBREEsQ0FyQkEsSUFxQkM7QUFkWSxjQUFNLFNBY2xCLENBQUEiLCJmaWxlIjoic3JjL2NvbnRyb2xsZXJzL2xvZ291dC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBJbmplY3R9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Um91dGVyfSBmcm9tICdhbmd1bGFyMi9yb3V0ZXInO1xuaW1wb3J0IHtDbGllbnR9IGZyb20gJ3NyYy9zZXJ2aWNlcy9hcGknO1xuXG5AQ29tcG9uZW50KHtcbiAgdmlld0luamVjdG9yOiBbQ2xpZW50XVxufSlcbkBWaWV3KHtcbiAgdGVtcGxhdGU6IFwiTG9nZ2luZyBvdXQuLlwiXG59KVxuXG5leHBvcnQgY2xhc3MgTG9nb3V0IHtcblxuXHRjb25zdHJ1Y3RvcihwdWJsaWMgY2xpZW50IDogQ2xpZW50LCBASW5qZWN0KFJvdXRlcikgcHVibGljIHJvdXRlcjogUm91dGVyKXtcblx0XHR0aGlzLmxvZ291dCgpO1xuXHR9XG5cblx0bG9nb3V0KCl7XG5cdFx0Ly9AdG9kbyBzZW5kIERFTEVURSB0byBhdXRoZW50aWNhdGlvbiBlbmRwb2ludFxuXHRcdHRoaXMucm91dGVyLm5hdmlnYXRlKCcvbG9naW4nKTtcblx0XHR3aW5kb3cuTG9nZ2VkSW4gPSBmYWxzZTtcblx0XHR0aGlzLnJvdXRlci5wYXJlbnQubmF2aWdhdGUoJy9sb2dpbicpO1xuXHRcdGNvbnNvbGUubG9nKHRoaXMucm91dGVyKTtcblx0XHR0aGlzLmNsaWVudC5kZWxldGUoJ2FwaS92MS9hdXRoZW50aWNhdGUnKTtcblx0fVxufSJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==