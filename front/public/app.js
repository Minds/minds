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
var logout_1 = require('./src/controllers/logout');
var newsfeed_1 = require('./src/controllers/newsfeed/newsfeed');
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
            { path: '/logout', component: logout_1.Logout, as: 'logout' },
            { path: '/newsfeed', component: newsfeed_1.Newsfeed, as: 'newsfeed' },
            { path: '/capture', component: capture_1.Capture, as: 'capture' },
            { path: '/discovery', component: newsfeed_1.Newsfeed, as: 'discovery' },
            { path: '/messenger', component: newsfeed_1.Newsfeed, as: 'messenger' },
            { path: '/notifications', component: newsfeed_1.Newsfeed, as: 'notifications' },
            { path: '/groups', component: newsfeed_1.Newsfeed, as: 'groups' },
            { path: '/:username', redirectTo: '/login' }
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLDRDQUE0QztBQUM1Qyx5QkFBMEQsbUJBQW1CLENBQUMsQ0FBQTtBQUM5RSx1QkFBdUUsaUJBQWlCLENBQUMsQ0FBQTtBQUV6Rix1QkFBcUIseUJBQXlCLENBQUMsQ0FBQTtBQUMvQywyQkFBeUIsNkJBQTZCLENBQUMsQ0FBQTtBQUV2RCxzQkFBb0IseUJBQXlCLENBQUMsQ0FBQTtBQUM5Qyx1QkFBcUIsMEJBQTBCLENBQUMsQ0FBQTtBQUNoRCx5QkFBdUIscUNBQXFDLENBQUMsQ0FBQTtBQUM3RCx3QkFBc0IsbUNBQW1DLENBQUMsQ0FBQTtBQUUxRDtJQXVCRUE7UUFDRUMsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsT0FBT0EsQ0FBQ0E7SUFDdEJBLENBQUNBO0lBekJIRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsV0FBV0E7U0FDdEJBLENBQUNBO1FBQ0RBLG9CQUFXQSxDQUFDQTtZQUNYQSxFQUFFQSxJQUFJQSxFQUFFQSxRQUFRQSxFQUFFQSxTQUFTQSxFQUFFQSxhQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxPQUFPQSxFQUFFQTtZQUNqREEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsZUFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBRUE7WUFDcERBLEVBQUVBLElBQUlBLEVBQUVBLFdBQVdBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxVQUFVQSxFQUFFQTtZQUMxREEsRUFBRUEsSUFBSUEsRUFBRUEsVUFBVUEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLFNBQVNBLEVBQUVBO1lBQ3ZEQSxFQUFFQSxJQUFJQSxFQUFFQSxZQUFZQSxFQUFFQSxTQUFTQSxFQUFFQSxtQkFBUUEsRUFBRUEsRUFBRUEsRUFBRUEsV0FBV0EsRUFBQ0E7WUFDM0RBLEVBQUVBLElBQUlBLEVBQUVBLFlBQVlBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUMzREEsRUFBRUEsSUFBSUEsRUFBRUEsZ0JBQWdCQSxFQUFFQSxTQUFTQSxFQUFFQSxtQkFBUUEsRUFBRUEsRUFBRUEsRUFBRUEsZUFBZUEsRUFBQ0E7WUFDbkVBLEVBQUVBLElBQUlBLEVBQUVBLFNBQVNBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxRQUFRQSxFQUFDQTtZQUVyREEsRUFBRUEsSUFBSUEsRUFBRUEsWUFBWUEsRUFBRUEsVUFBVUEsRUFBRUEsUUFBUUEsRUFBRUE7U0FDN0NBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHdCQUF3QkE7WUFDckNBLFVBQVVBLEVBQUVBLENBQUNBLGVBQU1BLEVBQUVBLHVCQUFVQSxFQUFFQSxxQkFBWUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQzNEQSxDQUFDQTs7Y0FRREE7SUFBREEsWUFBQ0E7QUFBREEsQ0ExQkEsSUEwQkM7QUFFRCxvQkFBUyxDQUFDLEtBQUssRUFBRSxDQUFDLDBCQUFpQixFQUFFLDBCQUFlLENBQUMsQ0FBQyxDQUFDIiwiZmlsZSI6ImFwcC5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vLyA8cmVmZXJlbmNlIHBhdGg9XCIuLi90eXBpbmdzL3RzZC5kLnRzXCIgLz5cbmltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBib290c3RyYXAsIGh0dHBJbmplY3RhYmxlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZUNvbmZpZywgUm91dGVyT3V0bGV0LCBSb3V0ZXJMaW5rLCByb3V0ZXJJbmplY3RhYmxlc30gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcblxuaW1wb3J0IHtUb3BiYXJ9IGZyb20gJy4vc3JjL2NvbXBvbmVudHMvdG9wYmFyJztcbmltcG9ydCB7TmF2aWdhdGlvbn0gZnJvbSAnLi9zcmMvY29tcG9uZW50cy9uYXZpZ2F0aW9uJztcblxuaW1wb3J0IHtMb2dpbn0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbG9naW4nO1xuaW1wb3J0IHtMb2dvdXR9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2xvZ291dCc7XG5pbXBvcnQge05ld3NmZWVkfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9uZXdzZmVlZC9uZXdzZmVlZCc7XG5pbXBvcnQge0NhcHR1cmV9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NhcHR1cmUvY2FwdHVyZSc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWFwcCcsXG59KVxuQFJvdXRlQ29uZmlnKFtcbiAgeyBwYXRoOiAnL2xvZ2luJywgY29tcG9uZW50OiBMb2dpbiwgYXM6ICdsb2dpbicgfSxcbiAgeyBwYXRoOiAnL2xvZ291dCcsIGNvbXBvbmVudDogTG9nb3V0LCBhczogJ2xvZ291dCcgfSxcdFxuICB7IHBhdGg6ICcvbmV3c2ZlZWQnLCBjb21wb25lbnQ6IE5ld3NmZWVkLCBhczogJ25ld3NmZWVkJyB9LFxuICB7IHBhdGg6ICcvY2FwdHVyZScsIGNvbXBvbmVudDogQ2FwdHVyZSwgYXM6ICdjYXB0dXJlJyB9LFxuICB7IHBhdGg6ICcvZGlzY292ZXJ5JywgY29tcG9uZW50OiBOZXdzZmVlZCwgYXM6ICdkaXNjb3ZlcnknfSxcbiAgeyBwYXRoOiAnL21lc3NlbmdlcicsIGNvbXBvbmVudDogTmV3c2ZlZWQsIGFzOiAnbWVzc2VuZ2VyJ30sXG4gIHsgcGF0aDogJy9ub3RpZmljYXRpb25zJywgY29tcG9uZW50OiBOZXdzZmVlZCwgYXM6ICdub3RpZmljYXRpb25zJ30sXG4gIHsgcGF0aDogJy9ncm91cHMnLCBjb21wb25lbnQ6IE5ld3NmZWVkLCBhczogJ2dyb3Vwcyd9LFxuICBcdFxuICB7IHBhdGg6ICcvOnVzZXJuYW1lJywgcmVkaXJlY3RUbzogJy9sb2dpbicgfVxuXSlcbkBWaWV3KHtcbiAgdGVtcGxhdGVVcmw6ICcuL3RlbXBsYXRlcy9pbmRleC5odG1sJyxcbiAgZGlyZWN0aXZlczogW1RvcGJhciwgTmF2aWdhdGlvbiwgUm91dGVyT3V0bGV0LCBSb3V0ZXJMaW5rXVxufSlcblxuY2xhc3MgTWluZHMge1xuICBuYW1lOiBzdHJpbmc7XG4gIFxuICBjb25zdHJ1Y3RvcigpIHtcbiAgICB0aGlzLm5hbWUgPSAnTWluZHMnO1xuICB9XG59XG5cbmJvb3RzdHJhcChNaW5kcywgW3JvdXRlckluamVjdGFibGVzLCBodHRwSW5qZWN0YWJsZXNdKTsiXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=