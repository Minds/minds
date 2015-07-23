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
/// <reference path="../typings/tsd.d.ts" />
var angular2_1 = require('angular2/angular2');
var router_1 = require('angular2/router');
var topbar_1 = require('./src/components/topbar');
var navigation_1 = require('./src/components/navigation');
var login_1 = require('./src/controllers/login');
var logout_1 = require('./src/controllers/logout');
var comingsoon_1 = require('./src/controllers/comingsoon');
var newsfeed_1 = require('./src/controllers/newsfeed/newsfeed');
var capture_1 = require('./src/controllers/capture/capture');
var discovery_1 = require('./src/controllers/discovery/discovery');
var channel_1 = require('./src/controllers/channels/channel');
var gatherings_1 = require('./src/plugins/gatherings/gatherings');
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
            { path: '/discovery/:filter', component: discovery_1.Discovery, as: 'discovery' },
            { path: '/discovery/:filter/:type', component: discovery_1.Discovery, as: 'discovery' },
            { path: '/messenger', component: gatherings_1.Gatherings, as: 'messenger' },
            { path: '/notifications', component: comingsoon_1.ComingSoon, as: 'notifications' },
            { path: '/groups', component: comingsoon_1.ComingSoon, as: 'groups' },
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLEFBQ0EsNENBRDRDO0FBQzVDLHlCQUEwRCxtQkFBbUIsQ0FBQyxDQUFBO0FBQzlFLHVCQUF1RSxpQkFBaUIsQ0FBQyxDQUFBO0FBRXpGLHVCQUFxQix5QkFBeUIsQ0FBQyxDQUFBO0FBQy9DLDJCQUF5Qiw2QkFBNkIsQ0FBQyxDQUFBO0FBRXZELHNCQUFvQix5QkFBeUIsQ0FBQyxDQUFBO0FBQzlDLHVCQUFxQiwwQkFBMEIsQ0FBQyxDQUFBO0FBQ2hELDJCQUF5Qiw4QkFBOEIsQ0FBQyxDQUFBO0FBQ3hELHlCQUF1QixxQ0FBcUMsQ0FBQyxDQUFBO0FBQzdELHdCQUFzQixtQ0FBbUMsQ0FBQyxDQUFBO0FBQzFELDBCQUF3Qix1Q0FBdUMsQ0FBQyxDQUFBO0FBQ2hFLHdCQUFzQixvQ0FBb0MsQ0FBQyxDQUFBO0FBQzNELDJCQUF5QixxQ0FBcUMsQ0FBQyxDQUFBO0FBRS9EO0lBMkJFQTtRQUNFQyxJQUFJQSxDQUFDQSxJQUFJQSxHQUFHQSxPQUFPQSxDQUFDQTtJQUN0QkEsQ0FBQ0E7SUE3QkhEO1FBQUNBLG9CQUFTQSxDQUFDQTtZQUNUQSxRQUFRQSxFQUFFQSxXQUFXQTtTQUN0QkEsQ0FBQ0E7UUFDREEsb0JBQVdBLENBQUNBO1lBQ1hBLEVBQUVBLElBQUlBLEVBQUVBLFFBQVFBLEVBQUVBLFNBQVNBLEVBQUVBLGFBQUtBLEVBQUVBLEVBQUVBLEVBQUVBLE9BQU9BLEVBQUVBO1lBQ2pEQSxFQUFFQSxJQUFJQSxFQUFFQSxTQUFTQSxFQUFFQSxTQUFTQSxFQUFFQSxlQUFNQSxFQUFFQSxFQUFFQSxFQUFFQSxRQUFRQSxFQUFFQTtZQUNwREEsRUFBRUEsSUFBSUEsRUFBRUEsV0FBV0EsRUFBRUEsU0FBU0EsRUFBRUEsbUJBQVFBLEVBQUVBLEVBQUVBLEVBQUVBLFVBQVVBLEVBQUVBO1lBQzFEQSxFQUFFQSxJQUFJQSxFQUFFQSxVQUFVQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBT0EsRUFBRUEsRUFBRUEsRUFBRUEsU0FBU0EsRUFBRUE7WUFFdkRBLEVBQUVBLElBQUlBLEVBQUVBLG9CQUFvQkEsRUFBRUEsU0FBU0EsRUFBRUEscUJBQVNBLEVBQUVBLEVBQUVBLEVBQUVBLFdBQVdBLEVBQUNBO1lBQ3BFQSxFQUFFQSxJQUFJQSxFQUFFQSwwQkFBMEJBLEVBQUVBLFNBQVNBLEVBQUVBLHFCQUFTQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUUxRUEsRUFBRUEsSUFBSUEsRUFBRUEsWUFBWUEsRUFBRUEsU0FBU0EsRUFBR0EsdUJBQVVBLEVBQUVBLEVBQUVBLEVBQUVBLFdBQVdBLEVBQUNBO1lBRTlEQSxFQUFFQSxJQUFJQSxFQUFFQSxnQkFBZ0JBLEVBQUVBLFNBQVNBLEVBQUVBLHVCQUFVQSxFQUFFQSxFQUFFQSxFQUFFQSxlQUFlQSxFQUFDQTtZQUNyRUEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsdUJBQVVBLEVBQUVBLEVBQUVBLEVBQUVBLFFBQVFBLEVBQUNBO1lBRXZEQSxFQUFFQSxJQUFJQSxFQUFFQSxZQUFZQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBT0EsRUFBRUEsRUFBRUEsRUFBRUEsU0FBU0EsRUFBRUE7U0FDMURBLENBQUNBO1FBQ0RBLGVBQUlBLENBQUNBO1lBQ0pBLFdBQVdBLEVBQUVBLHdCQUF3QkE7WUFDckNBLFVBQVVBLEVBQUVBLENBQUNBLGVBQU1BLEVBQUVBLHVCQUFVQSxFQUFFQSxxQkFBWUEsRUFBRUEsbUJBQVVBLENBQUNBO1NBQzNEQSxDQUFDQTs7Y0FRREE7SUFBREEsWUFBQ0E7QUFBREEsQ0E5QkEsQUE4QkNBLElBQUE7QUFFRCxvQkFBUyxDQUFDLEtBQUssRUFBRSxDQUFDLDBCQUFpQixFQUFFLDBCQUFlLENBQUMsQ0FBQyxDQUFDIiwiZmlsZSI6ImFwcC5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vLyA8cmVmZXJlbmNlIHBhdGg9XCIuLi90eXBpbmdzL3RzZC5kLnRzXCIgLz5cbmltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBib290c3RyYXAsIGh0dHBJbmplY3RhYmxlc30gZnJvbSAnYW5ndWxhcjIvYW5ndWxhcjInO1xuaW1wb3J0IHtSb3V0ZUNvbmZpZywgUm91dGVyT3V0bGV0LCBSb3V0ZXJMaW5rLCByb3V0ZXJJbmplY3RhYmxlc30gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcblxuaW1wb3J0IHtUb3BiYXJ9IGZyb20gJy4vc3JjL2NvbXBvbmVudHMvdG9wYmFyJztcbmltcG9ydCB7TmF2aWdhdGlvbn0gZnJvbSAnLi9zcmMvY29tcG9uZW50cy9uYXZpZ2F0aW9uJztcblxuaW1wb3J0IHtMb2dpbn0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbG9naW4nO1xuaW1wb3J0IHtMb2dvdXR9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2xvZ291dCc7XG5pbXBvcnQge0NvbWluZ1Nvb259IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NvbWluZ3Nvb24nO1xuaW1wb3J0IHtOZXdzZmVlZH0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvbmV3c2ZlZWQnO1xuaW1wb3J0IHtDYXB0dXJlfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9jYXB0dXJlL2NhcHR1cmUnO1xuaW1wb3J0IHtEaXNjb3Zlcnl9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2Rpc2NvdmVyeS9kaXNjb3ZlcnknO1xuaW1wb3J0IHtDaGFubmVsfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9jaGFubmVscy9jaGFubmVsJztcbmltcG9ydCB7R2F0aGVyaW5nc30gZnJvbSAnLi9zcmMvcGx1Z2lucy9nYXRoZXJpbmdzL2dhdGhlcmluZ3MnO1xuXG5AQ29tcG9uZW50KHtcbiAgc2VsZWN0b3I6ICdtaW5kcy1hcHAnLFxufSlcbkBSb3V0ZUNvbmZpZyhbXG4gIHsgcGF0aDogJy9sb2dpbicsIGNvbXBvbmVudDogTG9naW4sIGFzOiAnbG9naW4nIH0sXG4gIHsgcGF0aDogJy9sb2dvdXQnLCBjb21wb25lbnQ6IExvZ291dCwgYXM6ICdsb2dvdXQnIH0sXG4gIHsgcGF0aDogJy9uZXdzZmVlZCcsIGNvbXBvbmVudDogTmV3c2ZlZWQsIGFzOiAnbmV3c2ZlZWQnIH0sXG4gIHsgcGF0aDogJy9jYXB0dXJlJywgY29tcG9uZW50OiBDYXB0dXJlLCBhczogJ2NhcHR1cmUnIH0sXG5cbiAgeyBwYXRoOiAnL2Rpc2NvdmVyeS86ZmlsdGVyJywgY29tcG9uZW50OiBEaXNjb3ZlcnksIGFzOiAnZGlzY292ZXJ5J30sXG4gIHsgcGF0aDogJy9kaXNjb3ZlcnkvOmZpbHRlci86dHlwZScsIGNvbXBvbmVudDogRGlzY292ZXJ5LCBhczogJ2Rpc2NvdmVyeSd9LFxuXG4gIHsgcGF0aDogJy9tZXNzZW5nZXInLCBjb21wb25lbnQ6ICBHYXRoZXJpbmdzLCBhczogJ21lc3Nlbmdlcid9LFxuXG4gIHsgcGF0aDogJy9ub3RpZmljYXRpb25zJywgY29tcG9uZW50OiBDb21pbmdTb29uLCBhczogJ25vdGlmaWNhdGlvbnMnfSxcbiAgeyBwYXRoOiAnL2dyb3VwcycsIGNvbXBvbmVudDogQ29taW5nU29vbiwgYXM6ICdncm91cHMnfSxcblxuICB7IHBhdGg6ICcvOnVzZXJuYW1lJywgY29tcG9uZW50OiBDaGFubmVsLCBhczogJ2NoYW5uZWwnIH1cbl0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAnLi90ZW1wbGF0ZXMvaW5kZXguaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtUb3BiYXIsIE5hdmlnYXRpb24sIFJvdXRlck91dGxldCwgUm91dGVyTGlua11cbn0pXG5cbmNsYXNzIE1pbmRzIHtcbiAgbmFtZTogc3RyaW5nO1xuXG4gIGNvbnN0cnVjdG9yKCkge1xuICAgIHRoaXMubmFtZSA9ICdNaW5kcyc7XG4gIH1cbn1cblxuYm9vdHN0cmFwKE1pbmRzLCBbcm91dGVySW5qZWN0YWJsZXMsIGh0dHBJbmplY3RhYmxlc10pO1xuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9