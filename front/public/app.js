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
var router_1 = require('angular2/router');
var http_1 = require('http/http');
var topbar_1 = require('./src/components/topbar');
var navigation_1 = require('./src/components/navigation');
var login_1 = require('./src/controllers/login');
var logout_1 = require('./src/controllers/logout');
var newsfeed_1 = require('./src/controllers/newsfeed/newsfeed');
var capture_1 = require('./src/controllers/capture/capture');
var discovery_1 = require('./src/controllers/discovery/discovery');
var channel_1 = require('./src/controllers/channels/channel');
var notifications_1 = require('./src/controllers/notifications/notifications');
var gatherings_1 = require('./src/plugins/gatherings/gatherings');
var blog_1 = require('./src/plugins/blog/blog');
var groups_1 = require('./src/plugins/groups/groups');
var payments_1 = require('./src/plugins/payments/payments');
var Minds = (function () {
    function Minds() {
        this.name = 'Minds';
    }
    Minds = __decorate([
        angular2_1.Component({
            selector: 'minds-app',
        }),
        router_1.RouteConfig([
            new router_1.Route({ path: '/login', component: login_1.Login, as: 'login' }),
            { path: '/logout', component: logout_1.Logout, as: 'logout' },
            { path: '/newsfeed', component: newsfeed_1.Newsfeed, as: 'newsfeed' },
            { path: '/capture', component: capture_1.Capture, as: 'capture' },
            { path: '/discovery/:filter', component: discovery_1.Discovery, as: 'discovery' },
            { path: '/discovery/:filter/:type', component: discovery_1.Discovery, as: 'discovery' },
            { path: '/messenger', component: gatherings_1.Gatherings, as: 'messenger' },
            { path: '/blog/:filter', component: blog_1.Blog, as: 'blog' },
            { path: '/notifications', component: notifications_1.Notifications, as: 'notifications' },
            { path: '/groups/:filter', component: groups_1.Groups, as: 'groups' },
            { path: '/groups/create', component: groups_1.GroupsCreator, as: 'groups-create' },
            { path: '/groups/profile/:guid', component: groups_1.GroupsProfile, as: 'groups-profile' },
            { path: '/wallet', component: payments_1.Wallet, as: 'wallet' },
            { path: '/:username', component: channel_1.Channel, as: 'channel' },
            { path: '/:username/:filter', component: channel_1.Channel, as: 'channel-filter' },
            { path: '/', redirectTo: '/newsfeed' }
        ]),
        angular2_1.View({
            templateUrl: './templates/index.html',
            directives: [topbar_1.Topbar, navigation_1.Navigation, router_1.RouterOutlet, router_1.RouterLink]
        }), 
        __metadata('design:paramtypes', [])
    ], Minds);
    return Minds;
})();
angular2_1.bootstrap(Minds, [router_1.ROUTER_BINDINGS, http_1.HTTP_BINDINGS]);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLHlCQUF5QyxtQkFBbUIsQ0FBQyxDQUFBO0FBQzdELHVCQUE0RSxpQkFBaUIsQ0FBQyxDQUFBO0FBQzlGLHFCQUE0QixXQUFXLENBQUMsQ0FBQTtBQUV4Qyx1QkFBcUIseUJBQXlCLENBQUMsQ0FBQTtBQUMvQywyQkFBeUIsNkJBQTZCLENBQUMsQ0FBQTtBQUV2RCxzQkFBb0IseUJBQXlCLENBQUMsQ0FBQTtBQUM5Qyx1QkFBcUIsMEJBQTBCLENBQUMsQ0FBQTtBQUVoRCx5QkFBdUIscUNBQXFDLENBQUMsQ0FBQTtBQUM3RCx3QkFBc0IsbUNBQW1DLENBQUMsQ0FBQTtBQUMxRCwwQkFBd0IsdUNBQXVDLENBQUMsQ0FBQTtBQUNoRSx3QkFBZ0Usb0NBQW9DLENBQUMsQ0FBQTtBQUNyRyw4QkFBNEIsK0NBQStDLENBQUMsQ0FBQTtBQUs1RSwyQkFBeUIscUNBQXFDLENBQUMsQ0FBQTtBQUMvRCxxQkFBbUIseUJBQXlCLENBQUMsQ0FBQTtBQUM3Qyx1QkFBbUQsNkJBQTZCLENBQUMsQ0FBQTtBQUNqRix5QkFBcUIsaUNBQWlDLENBQUMsQ0FBQTtBQUV2RDtJQXNDRUE7UUFDRUMsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsT0FBT0EsQ0FBQ0E7SUFDdEJBLENBQUNBO0lBeENIRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsV0FBV0E7U0FDdEJBLENBQUNBO1FBQ0RBLG9CQUFXQSxDQUFDQTtZQUNYQSxJQUFJQSxjQUFLQSxDQUFDQSxFQUFFQSxJQUFJQSxFQUFFQSxRQUFRQSxFQUFFQSxTQUFTQSxFQUFFQSxhQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxPQUFPQSxFQUFFQSxDQUFDQTtZQUM1REEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsZUFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBRUE7WUFDcERBLEVBQUVBLElBQUlBLEVBQUVBLFdBQVdBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxVQUFVQSxFQUFFQTtZQUMxREEsRUFBRUEsSUFBSUEsRUFBRUEsVUFBVUEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLFNBQVNBLEVBQUVBO1lBRXZEQSxFQUFFQSxJQUFJQSxFQUFFQSxvQkFBb0JBLEVBQUVBLFNBQVNBLEVBQUVBLHFCQUFTQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUNwRUEsRUFBRUEsSUFBSUEsRUFBRUEsMEJBQTBCQSxFQUFFQSxTQUFTQSxFQUFFQSxxQkFBU0EsRUFBRUEsRUFBRUEsRUFBRUEsV0FBV0EsRUFBQ0E7WUFFMUVBLEVBQUVBLElBQUlBLEVBQUVBLFlBQVlBLEVBQUVBLFNBQVNBLEVBQUdBLHVCQUFVQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUU5REEsRUFBRUEsSUFBSUEsRUFBRUEsZUFBZUEsRUFBRUEsU0FBU0EsRUFBR0EsV0FBSUEsRUFBRUEsRUFBRUEsRUFBRUEsTUFBTUEsRUFBQ0E7WUFFdERBLEVBQUVBLElBQUlBLEVBQUVBLGdCQUFnQkEsRUFBRUEsU0FBU0EsRUFBRUEsNkJBQWFBLEVBQUVBLEVBQUVBLEVBQUVBLGVBQWVBLEVBQUNBO1lBRXhFQSxFQUFFQSxJQUFJQSxFQUFFQSxpQkFBaUJBLEVBQUVBLFNBQVNBLEVBQUVBLGVBQU1BLEVBQUVBLEVBQUVBLEVBQUVBLFFBQVFBLEVBQUNBO1lBQzNEQSxFQUFFQSxJQUFJQSxFQUFFQSxnQkFBZ0JBLEVBQUVBLFNBQVNBLEVBQUVBLHNCQUFhQSxFQUFFQSxFQUFFQSxFQUFFQSxlQUFlQSxFQUFDQTtZQUN4RUEsRUFBRUEsSUFBSUEsRUFBRUEsdUJBQXVCQSxFQUFFQSxTQUFTQSxFQUFFQSxzQkFBYUEsRUFBRUEsRUFBRUEsRUFBRUEsZ0JBQWdCQSxFQUFDQTtZQUVoRkEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU1BLEVBQUVBLEVBQUVBLEVBQUVBLFFBQVFBLEVBQUNBO1lBR25EQSxFQUFFQSxJQUFJQSxFQUFFQSxZQUFZQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBT0EsRUFBRUEsRUFBRUEsRUFBRUEsU0FBU0EsRUFBRUE7WUFDekRBLEVBQUVBLElBQUlBLEVBQUVBLG9CQUFvQkEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLGdCQUFnQkEsRUFBRUE7WUFFeEVBLEVBQUVBLElBQUlBLEVBQUVBLEdBQUdBLEVBQUVBLFVBQVVBLEVBQUVBLFdBQVdBLEVBQUVBO1NBQ3ZDQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSx3QkFBd0JBO1lBQ3JDQSxVQUFVQSxFQUFFQSxDQUFDQSxlQUFNQSxFQUFFQSx1QkFBVUEsRUFBRUEscUJBQVlBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUMzREEsQ0FBQ0E7O2NBUURBO0lBQURBLFlBQUNBO0FBQURBLENBekNBLEFBeUNDQSxJQUFBO0FBRUQsb0JBQVMsQ0FBQyxLQUFLLEVBQUUsQ0FBQyx3QkFBZSxFQUFFLG9CQUFhLENBQUMsQ0FBQyxDQUFDIiwiZmlsZSI6ImFwcC5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBib290c3RyYXB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Um91dGVDb25maWcsIFJvdXRlck91dGxldCwgUm91dGVyTGluaywgUm91dGUsIFJPVVRFUl9CSU5ESU5HU30gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7SFRUUF9CSU5ESU5HU30gZnJvbSAnaHR0cC9odHRwJztcblxuaW1wb3J0IHtUb3BiYXJ9IGZyb20gJy4vc3JjL2NvbXBvbmVudHMvdG9wYmFyJztcbmltcG9ydCB7TmF2aWdhdGlvbn0gZnJvbSAnLi9zcmMvY29tcG9uZW50cy9uYXZpZ2F0aW9uJztcblxuaW1wb3J0IHtMb2dpbn0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbG9naW4nO1xuaW1wb3J0IHtMb2dvdXR9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2xvZ291dCc7XG5pbXBvcnQge0NvbWluZ1Nvb259IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NvbWluZ3Nvb24nO1xuaW1wb3J0IHtOZXdzZmVlZH0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvbmV3c2ZlZWQnO1xuaW1wb3J0IHtDYXB0dXJlfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9jYXB0dXJlL2NhcHR1cmUnO1xuaW1wb3J0IHtEaXNjb3Zlcnl9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2Rpc2NvdmVyeS9kaXNjb3ZlcnknO1xuaW1wb3J0IHtDaGFubmVsLCBDaGFubmVsU3Vic2NyaWJlcnMsIENoYW5uZWxTdWJzY3JpcHRpb25zfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9jaGFubmVscy9jaGFubmVsJztcbmltcG9ydCB7Tm90aWZpY2F0aW9uc30gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbm90aWZpY2F0aW9ucy9ub3RpZmljYXRpb25zJztcblxuLyoqXG4gKiBUT0RPOiBMb2FkIHRoZXNlIGF1dG9tYWdpY2FsbHkgZnJvbSBndWxwXG4gKi9cbmltcG9ydCB7R2F0aGVyaW5nc30gZnJvbSAnLi9zcmMvcGx1Z2lucy9nYXRoZXJpbmdzL2dhdGhlcmluZ3MnO1xuaW1wb3J0IHtCbG9nfSBmcm9tICcuL3NyYy9wbHVnaW5zL2Jsb2cvYmxvZyc7XG5pbXBvcnQge0dyb3VwcywgR3JvdXBzUHJvZmlsZSwgR3JvdXBzQ3JlYXRvcn0gZnJvbSAnLi9zcmMvcGx1Z2lucy9ncm91cHMvZ3JvdXBzJztcbmltcG9ydCB7V2FsbGV0fSBmcm9tICcuL3NyYy9wbHVnaW5zL3BheW1lbnRzL3BheW1lbnRzJztcblxuQENvbXBvbmVudCh7XG4gIHNlbGVjdG9yOiAnbWluZHMtYXBwJyxcbn0pXG5AUm91dGVDb25maWcoW1xuICBuZXcgUm91dGUoeyBwYXRoOiAnL2xvZ2luJywgY29tcG9uZW50OiBMb2dpbiwgYXM6ICdsb2dpbicgfSksXG4gIHsgcGF0aDogJy9sb2dvdXQnLCBjb21wb25lbnQ6IExvZ291dCwgYXM6ICdsb2dvdXQnIH0sXG4gIHsgcGF0aDogJy9uZXdzZmVlZCcsIGNvbXBvbmVudDogTmV3c2ZlZWQsIGFzOiAnbmV3c2ZlZWQnIH0sXG4gIHsgcGF0aDogJy9jYXB0dXJlJywgY29tcG9uZW50OiBDYXB0dXJlLCBhczogJ2NhcHR1cmUnIH0sXG5cbiAgeyBwYXRoOiAnL2Rpc2NvdmVyeS86ZmlsdGVyJywgY29tcG9uZW50OiBEaXNjb3ZlcnksIGFzOiAnZGlzY292ZXJ5J30sXG4gIHsgcGF0aDogJy9kaXNjb3ZlcnkvOmZpbHRlci86dHlwZScsIGNvbXBvbmVudDogRGlzY292ZXJ5LCBhczogJ2Rpc2NvdmVyeSd9LFxuXG4gIHsgcGF0aDogJy9tZXNzZW5nZXInLCBjb21wb25lbnQ6ICBHYXRoZXJpbmdzLCBhczogJ21lc3Nlbmdlcid9LFxuXG4gIHsgcGF0aDogJy9ibG9nLzpmaWx0ZXInLCBjb21wb25lbnQ6ICBCbG9nLCBhczogJ2Jsb2cnfSxcblxuICB7IHBhdGg6ICcvbm90aWZpY2F0aW9ucycsIGNvbXBvbmVudDogTm90aWZpY2F0aW9ucywgYXM6ICdub3RpZmljYXRpb25zJ30sXG5cbiAgeyBwYXRoOiAnL2dyb3Vwcy86ZmlsdGVyJywgY29tcG9uZW50OiBHcm91cHMsIGFzOiAnZ3JvdXBzJ30sXG4gIHsgcGF0aDogJy9ncm91cHMvY3JlYXRlJywgY29tcG9uZW50OiBHcm91cHNDcmVhdG9yLCBhczogJ2dyb3Vwcy1jcmVhdGUnfSxcbiAgeyBwYXRoOiAnL2dyb3Vwcy9wcm9maWxlLzpndWlkJywgY29tcG9uZW50OiBHcm91cHNQcm9maWxlLCBhczogJ2dyb3Vwcy1wcm9maWxlJ30sXG5cbiAgeyBwYXRoOiAnL3dhbGxldCcsIGNvbXBvbmVudDogV2FsbGV0LCBhczogJ3dhbGxldCd9LFxuXG5cbiAgeyBwYXRoOiAnLzp1c2VybmFtZScsIGNvbXBvbmVudDogQ2hhbm5lbCwgYXM6ICdjaGFubmVsJyB9LFxuICB7IHBhdGg6ICcvOnVzZXJuYW1lLzpmaWx0ZXInLCBjb21wb25lbnQ6IENoYW5uZWwsIGFzOiAnY2hhbm5lbC1maWx0ZXInIH0sXG5cbiAgeyBwYXRoOiAnLycsIHJlZGlyZWN0VG86ICcvbmV3c2ZlZWQnIH1cbl0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAnLi90ZW1wbGF0ZXMvaW5kZXguaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtUb3BiYXIsIE5hdmlnYXRpb24sIFJvdXRlck91dGxldCwgUm91dGVyTGlua11cbn0pXG5cbmNsYXNzIE1pbmRzIHtcbiAgbmFtZTogc3RyaW5nO1xuXG4gIGNvbnN0cnVjdG9yKCkge1xuICAgIHRoaXMubmFtZSA9ICdNaW5kcyc7XG4gIH1cbn1cblxuYm9vdHN0cmFwKE1pbmRzLCBbUk9VVEVSX0JJTkRJTkdTLCBIVFRQX0JJTkRJTkdTXSk7XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=