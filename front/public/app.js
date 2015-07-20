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
var channel_1 = require('./src/controllers/channels/channel');
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
            { path: '/:username', component: channel_1.Channel, as: 'channel' }
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLEFBQ0EsNENBRDRDO0FBQzVDLHlCQUEwRCxtQkFBbUIsQ0FBQyxDQUFBO0FBQzlFLHVCQUF1RSxpQkFBaUIsQ0FBQyxDQUFBO0FBRXpGLHVCQUFxQix5QkFBeUIsQ0FBQyxDQUFBO0FBQy9DLDJCQUF5Qiw2QkFBNkIsQ0FBQyxDQUFBO0FBRXZELHNCQUFvQix5QkFBeUIsQ0FBQyxDQUFBO0FBQzlDLHVCQUFxQiwwQkFBMEIsQ0FBQyxDQUFBO0FBQ2hELHlCQUF1QixxQ0FBcUMsQ0FBQyxDQUFBO0FBQzdELHdCQUFzQixtQ0FBbUMsQ0FBQyxDQUFBO0FBQzFELHdCQUFzQixvQ0FBb0MsQ0FBQyxDQUFBO0FBRTNEO0lBdUJFQTtRQUNFQyxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxPQUFPQSxDQUFDQTtJQUN0QkEsQ0FBQ0E7SUF6QkhEO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxXQUFXQTtTQUN0QkEsQ0FBQ0E7UUFDREEsb0JBQVdBLENBQUNBO1lBQ1hBLEVBQUVBLElBQUlBLEVBQUVBLFFBQVFBLEVBQUVBLFNBQVNBLEVBQUVBLGFBQUtBLEVBQUVBLEVBQUVBLEVBQUVBLE9BQU9BLEVBQUVBO1lBQ2pEQSxFQUFFQSxJQUFJQSxFQUFFQSxTQUFTQSxFQUFFQSxTQUFTQSxFQUFFQSxlQUFNQSxFQUFFQSxFQUFFQSxFQUFFQSxRQUFRQSxFQUFFQTtZQUNwREEsRUFBRUEsSUFBSUEsRUFBRUEsV0FBV0EsRUFBRUEsU0FBU0EsRUFBRUEsbUJBQVFBLEVBQUVBLEVBQUVBLEVBQUVBLFVBQVVBLEVBQUVBO1lBQzFEQSxFQUFFQSxJQUFJQSxFQUFFQSxVQUFVQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBT0EsRUFBRUEsRUFBRUEsRUFBRUEsU0FBU0EsRUFBRUE7WUFDdkRBLEVBQUVBLElBQUlBLEVBQUVBLFlBQVlBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUMzREEsRUFBRUEsSUFBSUEsRUFBRUEsWUFBWUEsRUFBRUEsU0FBU0EsRUFBRUEsbUJBQVFBLEVBQUVBLEVBQUVBLEVBQUVBLFdBQVdBLEVBQUNBO1lBQzNEQSxFQUFFQSxJQUFJQSxFQUFFQSxnQkFBZ0JBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxlQUFlQSxFQUFDQTtZQUNuRUEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsbUJBQVFBLEVBQUVBLEVBQUVBLEVBQUVBLFFBQVFBLEVBQUNBO1lBRXJEQSxFQUFFQSxJQUFJQSxFQUFFQSxZQUFZQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBT0EsRUFBRUEsRUFBRUEsRUFBRUEsU0FBU0EsRUFBRUE7U0FDMURBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHdCQUF3QkE7WUFDckNBLFVBQVVBLEVBQUVBLENBQUNBLGVBQU1BLEVBQUVBLHVCQUFVQSxFQUFFQSxxQkFBWUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQzNEQSxDQUFDQTs7Y0FRREE7SUFBREEsWUFBQ0E7QUFBREEsQ0ExQkEsQUEwQkNBLElBQUE7QUFFRCxvQkFBUyxDQUFDLEtBQUssRUFBRSxDQUFDLDBCQUFpQixFQUFFLDBCQUFlLENBQUMsQ0FBQyxDQUFDIiwiZmlsZSI6ImFwcC5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vLyA8cmVmZXJlbmNlIHBhdGg9XCIuLi90eXBpbmdzL3RzZC5kLnRzXCIgLz5cbmltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBib290c3RyYXAsIGh0dHBJbmplY3RhYmxlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZUNvbmZpZywgUm91dGVyT3V0bGV0LCBSb3V0ZXJMaW5rLCByb3V0ZXJJbmplY3RhYmxlc30gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcblxuaW1wb3J0IHtUb3BiYXJ9IGZyb20gJy4vc3JjL2NvbXBvbmVudHMvdG9wYmFyJztcbmltcG9ydCB7TmF2aWdhdGlvbn0gZnJvbSAnLi9zcmMvY29tcG9uZW50cy9uYXZpZ2F0aW9uJztcblxuaW1wb3J0IHtMb2dpbn0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbG9naW4nO1xuaW1wb3J0IHtMb2dvdXR9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2xvZ291dCc7XG5pbXBvcnQge05ld3NmZWVkfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9uZXdzZmVlZC9uZXdzZmVlZCc7XG5pbXBvcnQge0NhcHR1cmV9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NhcHR1cmUvY2FwdHVyZSc7XG5pbXBvcnQge0NoYW5uZWx9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NoYW5uZWxzL2NoYW5uZWwnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1hcHAnLFxufSlcbkBSb3V0ZUNvbmZpZyhbXG4gIHsgcGF0aDogJy9sb2dpbicsIGNvbXBvbmVudDogTG9naW4sIGFzOiAnbG9naW4nIH0sXG4gIHsgcGF0aDogJy9sb2dvdXQnLCBjb21wb25lbnQ6IExvZ291dCwgYXM6ICdsb2dvdXQnIH0sXG4gIHsgcGF0aDogJy9uZXdzZmVlZCcsIGNvbXBvbmVudDogTmV3c2ZlZWQsIGFzOiAnbmV3c2ZlZWQnIH0sXG4gIHsgcGF0aDogJy9jYXB0dXJlJywgY29tcG9uZW50OiBDYXB0dXJlLCBhczogJ2NhcHR1cmUnIH0sXG4gIHsgcGF0aDogJy9kaXNjb3ZlcnknLCBjb21wb25lbnQ6IE5ld3NmZWVkLCBhczogJ2Rpc2NvdmVyeSd9LFxuICB7IHBhdGg6ICcvbWVzc2VuZ2VyJywgY29tcG9uZW50OiBOZXdzZmVlZCwgYXM6ICdtZXNzZW5nZXInfSxcbiAgeyBwYXRoOiAnL25vdGlmaWNhdGlvbnMnLCBjb21wb25lbnQ6IE5ld3NmZWVkLCBhczogJ25vdGlmaWNhdGlvbnMnfSxcbiAgeyBwYXRoOiAnL2dyb3VwcycsIGNvbXBvbmVudDogTmV3c2ZlZWQsIGFzOiAnZ3JvdXBzJ30sXG5cbiAgeyBwYXRoOiAnLzp1c2VybmFtZScsIGNvbXBvbmVudDogQ2hhbm5lbCwgYXM6ICdjaGFubmVsJyB9XG5dKVxuQFZpZXcoe1xuICB0ZW1wbGF0ZVVybDogJy4vdGVtcGxhdGVzL2luZGV4Lmh0bWwnLFxuICBkaXJlY3RpdmVzOiBbVG9wYmFyLCBOYXZpZ2F0aW9uLCBSb3V0ZXJPdXRsZXQsIFJvdXRlckxpbmtdXG59KVxuXG5jbGFzcyBNaW5kcyB7XG4gIG5hbWU6IHN0cmluZztcblxuICBjb25zdHJ1Y3RvcigpIHtcbiAgICB0aGlzLm5hbWUgPSAnTWluZHMnO1xuICB9XG59XG5cbmJvb3RzdHJhcChNaW5kcywgW3JvdXRlckluamVjdGFibGVzLCBodHRwSW5qZWN0YWJsZXNdKTtcbiJdLCJzb3VyY2VSb290IjoiL3NvdXJjZS8ifQ==