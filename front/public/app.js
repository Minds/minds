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
/// <reference path="../typings/tsd.d.ts" />
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
var topbar_1 = require('./src/components/topbar');
var navigation_1 = require('./src/components/navigation');
var login_1 = require('./src/controllers/login');
var newsfeed_1 = require('./src/controllers/newsfeed');
var capture_1 = require('./src/controllers/capture/capture');
var Minds = (function () {
    function Minds() {
        this.name = 'Minds';
    }
    Minds = __decorate([
        angular2_1.Component({
            selector: 'minds-app',
        }),
        router_1.RouteConfig([
            { path: '/login', component: login_1.Login, as: 'login' },
            { path: '/newsfeed', component: newsfeed_1.Newsfeed, as: 'newsfeed' },
            { path: '/capture', component: capture_1.Capture, as: 'capture' }
        ]),
        angular2_1.View({
            templateUrl: './templates/index.html',
            directives: [topbar_1.Topbar, navigation_1.Navigation, router_1.RouterOutlet, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [])
    ], Minds);
    return Minds;
})();
angular2_1.bootstrap(Minds, [router_1.routerInjectables, angular2_1.httpInjectables]);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLDRDQUE0QztBQUM1Qyx5QkFBMEQsbUJBQW1CLENBQUMsQ0FBQTtBQUM5RSx1QkFBdUUsaUJBQWlCLENBQUMsQ0FBQTtBQUV6Rix1QkFBcUIseUJBQXlCLENBQUMsQ0FBQTtBQUMvQywyQkFBeUIsNkJBQTZCLENBQUMsQ0FBQTtBQUV2RCxzQkFBb0IseUJBQXlCLENBQUMsQ0FBQTtBQUM5Qyx5QkFBdUIsNEJBQTRCLENBQUMsQ0FBQTtBQUNwRCx3QkFBc0IsbUNBQW1DLENBQUMsQ0FBQTtBQUUxRDtJQWdCRUE7UUFDRUMsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsT0FBT0EsQ0FBQ0E7SUFDdEJBLENBQUNBO0lBbEJIRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsV0FBV0E7U0FDdEJBLENBQUNBO1FBQ0RBLG9CQUFXQSxDQUFDQTtZQUNYQSxFQUFFQSxJQUFJQSxFQUFFQSxRQUFRQSxFQUFFQSxTQUFTQSxFQUFFQSxhQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxPQUFPQSxFQUFFQTtZQUNqREEsRUFBRUEsSUFBSUEsRUFBRUEsV0FBV0EsRUFBRUEsU0FBU0EsRUFBRUEsbUJBQVFBLEVBQUVBLEVBQUVBLEVBQUVBLFVBQVVBLEVBQUVBO1lBQzFEQSxFQUFFQSxJQUFJQSxFQUFFQSxVQUFVQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBT0EsRUFBRUEsRUFBRUEsRUFBRUEsU0FBU0EsRUFBRUE7U0FDeERBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHdCQUF3QkE7WUFDckNBLFVBQVVBLEVBQUVBLENBQUNBLGVBQU1BLEVBQUVBLHVCQUFVQSxFQUFFQSxxQkFBWUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQzNEQSxDQUFDQTs7Y0FRREE7SUFBREEsWUFBQ0E7QUFBREEsQ0FuQkEsSUFtQkM7QUFFRCxvQkFBUyxDQUFDLEtBQUssRUFBRSxDQUFDLDBCQUFpQixFQUFFLDBCQUFlLENBQUMsQ0FBQyxDQUFDIiwiZmlsZSI6ImFwcC5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vLyA8cmVmZXJlbmNlIHBhdGg9XCIuLi90eXBpbmdzL3RzZC5kLnRzXCIgLz5cbmltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBib290c3RyYXAsIGh0dHBJbmplY3RhYmxlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZUNvbmZpZywgUm91dGVyT3V0bGV0LCBSb3V0ZXJMaW5rLCByb3V0ZXJJbmplY3RhYmxlc30gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcblxuaW1wb3J0IHtUb3BiYXJ9IGZyb20gJy4vc3JjL2NvbXBvbmVudHMvdG9wYmFyJztcbmltcG9ydCB7TmF2aWdhdGlvbn0gZnJvbSAnLi9zcmMvY29tcG9uZW50cy9uYXZpZ2F0aW9uJztcblxuaW1wb3J0IHtMb2dpbn0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbG9naW4nO1xuaW1wb3J0IHtOZXdzZmVlZH0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQnO1xuaW1wb3J0IHtDYXB0dXJlfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9jYXB0dXJlL2NhcHR1cmUnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1hcHAnLFxufSlcbkBSb3V0ZUNvbmZpZyhbXG4gIHsgcGF0aDogJy9sb2dpbicsIGNvbXBvbmVudDogTG9naW4sIGFzOiAnbG9naW4nIH0sXG4gIHsgcGF0aDogJy9uZXdzZmVlZCcsIGNvbXBvbmVudDogTmV3c2ZlZWQsIGFzOiAnbmV3c2ZlZWQnIH0sXG4gIHsgcGF0aDogJy9jYXB0dXJlJywgY29tcG9uZW50OiBDYXB0dXJlLCBhczogJ2NhcHR1cmUnIH1cbl0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAnLi90ZW1wbGF0ZXMvaW5kZXguaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtUb3BiYXIsIE5hdmlnYXRpb24sIFJvdXRlck91dGxldCwgUm91dGVyTGlua11cbn0pXG5cbmNsYXNzIE1pbmRzIHtcbiAgbmFtZTogc3RyaW5nO1xuICBcbiAgY29uc3RydWN0b3IoKSB7XG4gICAgdGhpcy5uYW1lID0gJ01pbmRzJztcbiAgfVxufVxuXG5ib290c3RyYXAoTWluZHMsIFtyb3V0ZXJJbmplY3RhYmxlcywgaHR0cEluamVjdGFibGVzXSk7Il0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9