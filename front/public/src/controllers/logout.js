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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbInNyYy9jb250cm9sbGVycy9sb2dvdXQudHMiXSwibmFtZXMiOlsiTG9nb3V0IiwiTG9nb3V0LmNvbnN0cnVjdG9yIiwiTG9nb3V0LmxvZ291dCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7QUFBQSx5QkFBc0MsbUJBQW1CLENBQUMsQ0FBQTtBQUMxRCx1QkFBcUIsaUJBQWlCLENBQUMsQ0FBQTtBQUN2QyxvQkFBcUIsa0JBQWtCLENBQUMsQ0FBQTtBQUV4QztJQVNDQSxnQkFBbUJBLE1BQWVBLEVBQXlCQSxNQUFjQTtRQUF0REMsV0FBTUEsR0FBTkEsTUFBTUEsQ0FBU0E7UUFBeUJBLFdBQU1BLEdBQU5BLE1BQU1BLENBQVFBO1FBQ3hFQSxJQUFJQSxDQUFDQSxNQUFNQSxFQUFFQSxDQUFDQTtJQUNmQSxDQUFDQTtJQUVERCx1QkFBTUEsR0FBTkE7UUFFQ0UsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsUUFBUUEsQ0FBQ0EsQ0FBQ0E7UUFDL0JBLE1BQU1BLENBQUNBLFFBQVFBLEdBQUdBLEtBQUtBLENBQUNBO1FBQ3hCQSxJQUFJQSxDQUFDQSxNQUFNQSxDQUFDQSxNQUFNQSxDQUFDQSxRQUFRQSxDQUFDQSxRQUFRQSxDQUFDQSxDQUFDQTtRQUN0Q0EsSUFBSUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EsTUFBTUEsQ0FBQ0EscUJBQXFCQSxDQUFDQSxDQUFDQTtJQUMzQ0EsQ0FBQ0E7SUFuQkZGO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxZQUFZQSxFQUFFQSxDQUFDQSxZQUFNQSxDQUFDQTtTQUN2QkEsQ0FBQ0E7UUFDREEsZUFBSUEsQ0FBQ0E7WUFDSkEsUUFBUUEsRUFBRUEsZUFBZUE7U0FDMUJBLENBQUNBO1FBSW1DQSxXQUFDQSxpQkFBTUEsQ0FBQ0EsZUFBTUEsQ0FBQ0EsQ0FBQUE7O2VBV25EQTtJQUFEQSxhQUFDQTtBQUFEQSxDQXBCQSxJQW9CQztBQWJZLGNBQU0sU0FhbEIsQ0FBQSIsImZpbGUiOiJzcmMvY29udHJvbGxlcnMvbG9nb3V0LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtDb21wb25lbnQsIFZpZXcsIEluamVjdH0gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZXJ9IGZyb20gJ2FuZ3VsYXIyL3JvdXRlcic7XG5pbXBvcnQge0NsaWVudH0gZnJvbSAnc3JjL3NlcnZpY2VzL2FwaSc7XG5cbkBDb21wb25lbnQoe1xuICB2aWV3SW5qZWN0b3I6IFtDbGllbnRdXG59KVxuQFZpZXcoe1xuICB0ZW1wbGF0ZTogXCJMb2dnaW5nIG91dC4uXCJcbn0pXG5cbmV4cG9ydCBjbGFzcyBMb2dvdXQge1xuXG5cdGNvbnN0cnVjdG9yKHB1YmxpYyBjbGllbnQgOiBDbGllbnQsIEBJbmplY3QoUm91dGVyKSBwdWJsaWMgcm91dGVyOiBSb3V0ZXIpe1xuXHRcdHRoaXMubG9nb3V0KCk7XG5cdH1cblxuXHRsb2dvdXQoKXtcblx0XHQvL0B0b2RvIHNlbmQgREVMRVRFIHRvIGF1dGhlbnRpY2F0aW9uIGVuZHBvaW50XG5cdFx0dGhpcy5yb3V0ZXIubmF2aWdhdGUoJy9sb2dpbicpO1xuXHRcdHdpbmRvdy5Mb2dnZWRJbiA9IGZhbHNlO1xuXHRcdHRoaXMucm91dGVyLnBhcmVudC5uYXZpZ2F0ZSgnL2xvZ2luJyk7XG5cdFx0dGhpcy5jbGllbnQuZGVsZXRlKCdhcGkvdjEvYXV0aGVudGljYXRlJyk7XG5cdH1cbn0iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=