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
var groups_1 = require('./src/plugins/groups/groups');
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
            { path: '/notifications', component: notifications_1.Notifications, as: 'notifications' },
            { path: '/groups/:filter', component: groups_1.Groups, as: 'groups' },
            { path: '/groups/create', component: groups_1.GroupsCreator, as: 'groups-create' },
            { path: '/groups/profile/:guid', component: groups_1.GroupsProfile, as: 'groups-profile' },
            { path: '/:username', component: channel_1.Channel, as: 'channel' },
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImFwcC50cyJdLCJuYW1lcyI6WyJNaW5kcyIsIk1pbmRzLmNvbnN0cnVjdG9yIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7OztBQUFBLEFBQ0EsNENBRDRDO0FBQzVDLHlCQUF5QyxtQkFBbUIsQ0FBQyxDQUFBO0FBQzdELHVCQUE0RSxpQkFBaUIsQ0FBQyxDQUFBO0FBQzlGLHFCQUE0QixXQUFXLENBQUMsQ0FBQTtBQUV4Qyx1QkFBcUIseUJBQXlCLENBQUMsQ0FBQTtBQUMvQywyQkFBeUIsNkJBQTZCLENBQUMsQ0FBQTtBQUV2RCxzQkFBb0IseUJBQXlCLENBQUMsQ0FBQTtBQUM5Qyx1QkFBcUIsMEJBQTBCLENBQUMsQ0FBQTtBQUVoRCx5QkFBdUIscUNBQXFDLENBQUMsQ0FBQTtBQUM3RCx3QkFBc0IsbUNBQW1DLENBQUMsQ0FBQTtBQUMxRCwwQkFBd0IsdUNBQXVDLENBQUMsQ0FBQTtBQUNoRSx3QkFBc0Isb0NBQW9DLENBQUMsQ0FBQTtBQUMzRCw4QkFBNEIsK0NBQStDLENBQUMsQ0FBQTtBQUs1RSwyQkFBeUIscUNBQXFDLENBQUMsQ0FBQTtBQUMvRCx1QkFBbUQsNkJBQTZCLENBQUMsQ0FBQTtBQUVqRjtJQWlDRUE7UUFDRUMsSUFBSUEsQ0FBQ0EsSUFBSUEsR0FBR0EsT0FBT0EsQ0FBQ0E7SUFDdEJBLENBQUNBO0lBbkNIRDtRQUFDQSxvQkFBU0EsQ0FBQ0E7WUFDVEEsUUFBUUEsRUFBRUEsV0FBV0E7U0FDdEJBLENBQUNBO1FBQ0RBLG9CQUFXQSxDQUFDQTtZQUNYQSxJQUFJQSxjQUFLQSxDQUFDQSxFQUFFQSxJQUFJQSxFQUFFQSxRQUFRQSxFQUFFQSxTQUFTQSxFQUFFQSxhQUFLQSxFQUFFQSxFQUFFQSxFQUFFQSxPQUFPQSxFQUFFQSxDQUFDQTtZQUM1REEsRUFBRUEsSUFBSUEsRUFBRUEsU0FBU0EsRUFBRUEsU0FBU0EsRUFBRUEsZUFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBRUE7WUFDcERBLEVBQUVBLElBQUlBLEVBQUVBLFdBQVdBLEVBQUVBLFNBQVNBLEVBQUVBLG1CQUFRQSxFQUFFQSxFQUFFQSxFQUFFQSxVQUFVQSxFQUFFQTtZQUMxREEsRUFBRUEsSUFBSUEsRUFBRUEsVUFBVUEsRUFBRUEsU0FBU0EsRUFBRUEsaUJBQU9BLEVBQUVBLEVBQUVBLEVBQUVBLFNBQVNBLEVBQUVBO1lBRXZEQSxFQUFFQSxJQUFJQSxFQUFFQSxvQkFBb0JBLEVBQUVBLFNBQVNBLEVBQUVBLHFCQUFTQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUNwRUEsRUFBRUEsSUFBSUEsRUFBRUEsMEJBQTBCQSxFQUFFQSxTQUFTQSxFQUFFQSxxQkFBU0EsRUFBRUEsRUFBRUEsRUFBRUEsV0FBV0EsRUFBQ0E7WUFFMUVBLEVBQUVBLElBQUlBLEVBQUVBLFlBQVlBLEVBQUVBLFNBQVNBLEVBQUdBLHVCQUFVQSxFQUFFQSxFQUFFQSxFQUFFQSxXQUFXQSxFQUFDQTtZQUU5REEsRUFBRUEsSUFBSUEsRUFBRUEsZ0JBQWdCQSxFQUFFQSxTQUFTQSxFQUFFQSw2QkFBYUEsRUFBRUEsRUFBRUEsRUFBRUEsZUFBZUEsRUFBQ0E7WUFFeEVBLEVBQUVBLElBQUlBLEVBQUVBLGlCQUFpQkEsRUFBRUEsU0FBU0EsRUFBRUEsZUFBTUEsRUFBRUEsRUFBRUEsRUFBRUEsUUFBUUEsRUFBQ0E7WUFDM0RBLEVBQUVBLElBQUlBLEVBQUVBLGdCQUFnQkEsRUFBRUEsU0FBU0EsRUFBRUEsc0JBQWFBLEVBQUVBLEVBQUVBLEVBQUVBLGVBQWVBLEVBQUNBO1lBQ3hFQSxFQUFFQSxJQUFJQSxFQUFFQSx1QkFBdUJBLEVBQUVBLFNBQVNBLEVBQUVBLHNCQUFhQSxFQUFFQSxFQUFFQSxFQUFFQSxnQkFBZ0JBLEVBQUNBO1lBR2hGQSxFQUFFQSxJQUFJQSxFQUFFQSxZQUFZQSxFQUFFQSxTQUFTQSxFQUFFQSxpQkFBT0EsRUFBRUEsRUFBRUEsRUFBRUEsU0FBU0EsRUFBRUE7WUFFekRBLEVBQUVBLElBQUlBLEVBQUVBLEdBQUdBLEVBQUVBLFVBQVVBLEVBQUVBLFdBQVdBLEVBQUVBO1NBQ3ZDQSxDQUFDQTtRQUNEQSxlQUFJQSxDQUFDQTtZQUNKQSxXQUFXQSxFQUFFQSx3QkFBd0JBO1lBQ3JDQSxVQUFVQSxFQUFFQSxDQUFDQSxlQUFNQSxFQUFFQSx1QkFBVUEsRUFBRUEscUJBQVlBLEVBQUVBLG1CQUFVQSxDQUFDQTtTQUMzREEsQ0FBQ0E7O2NBUURBO0lBQURBLFlBQUNBO0FBQURBLENBcENBLEFBb0NDQSxJQUFBO0FBRUQsb0JBQVMsQ0FBQyxLQUFLLEVBQUUsQ0FBQyx3QkFBZSxFQUFFLG9CQUFhLENBQUMsQ0FBQyxDQUFDIiwiZmlsZSI6ImFwcC5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vLyA8cmVmZXJlbmNlIHBhdGg9XCIuLi90eXBpbmdzL3RzZC5kLnRzXCIgLz5cbmltcG9ydCB7Q29tcG9uZW50LCBWaWV3LCBib290c3RyYXB9IGZyb20gJ2FuZ3VsYXIyL2FuZ3VsYXIyJztcbmltcG9ydCB7Um91dGVDb25maWcsIFJvdXRlck91dGxldCwgUm91dGVyTGluaywgUm91dGUsIFJPVVRFUl9CSU5ESU5HU30gZnJvbSAnYW5ndWxhcjIvcm91dGVyJztcbmltcG9ydCB7SFRUUF9CSU5ESU5HU30gZnJvbSAnaHR0cC9odHRwJztcblxuaW1wb3J0IHtUb3BiYXJ9IGZyb20gJy4vc3JjL2NvbXBvbmVudHMvdG9wYmFyJztcbmltcG9ydCB7TmF2aWdhdGlvbn0gZnJvbSAnLi9zcmMvY29tcG9uZW50cy9uYXZpZ2F0aW9uJztcblxuaW1wb3J0IHtMb2dpbn0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbG9naW4nO1xuaW1wb3J0IHtMb2dvdXR9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2xvZ291dCc7XG5pbXBvcnQge0NvbWluZ1Nvb259IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2NvbWluZ3Nvb24nO1xuaW1wb3J0IHtOZXdzZmVlZH0gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbmV3c2ZlZWQvbmV3c2ZlZWQnO1xuaW1wb3J0IHtDYXB0dXJlfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9jYXB0dXJlL2NhcHR1cmUnO1xuaW1wb3J0IHtEaXNjb3Zlcnl9IGZyb20gJy4vc3JjL2NvbnRyb2xsZXJzL2Rpc2NvdmVyeS9kaXNjb3ZlcnknO1xuaW1wb3J0IHtDaGFubmVsfSBmcm9tICcuL3NyYy9jb250cm9sbGVycy9jaGFubmVscy9jaGFubmVsJztcbmltcG9ydCB7Tm90aWZpY2F0aW9uc30gZnJvbSAnLi9zcmMvY29udHJvbGxlcnMvbm90aWZpY2F0aW9ucy9ub3RpZmljYXRpb25zJztcblxuLyoqXG4gKiBUT0RPOiBMb2FkIHRoZXNlIGF1dG9tYWdpY2FsbHkgZnJvbSBndWxwXG4gKi9cbmltcG9ydCB7R2F0aGVyaW5nc30gZnJvbSAnLi9zcmMvcGx1Z2lucy9nYXRoZXJpbmdzL2dhdGhlcmluZ3MnO1xuaW1wb3J0IHtHcm91cHMsIEdyb3Vwc1Byb2ZpbGUsIEdyb3Vwc0NyZWF0b3J9IGZyb20gJy4vc3JjL3BsdWdpbnMvZ3JvdXBzL2dyb3Vwcyc7XG5cbkBDb21wb25lbnQoe1xuICBzZWxlY3RvcjogJ21pbmRzLWFwcCcsXG59KVxuQFJvdXRlQ29uZmlnKFtcbiAgbmV3IFJvdXRlKHsgcGF0aDogJy9sb2dpbicsIGNvbXBvbmVudDogTG9naW4sIGFzOiAnbG9naW4nIH0pLFxuICB7IHBhdGg6ICcvbG9nb3V0JywgY29tcG9uZW50OiBMb2dvdXQsIGFzOiAnbG9nb3V0JyB9LFxuICB7IHBhdGg6ICcvbmV3c2ZlZWQnLCBjb21wb25lbnQ6IE5ld3NmZWVkLCBhczogJ25ld3NmZWVkJyB9LFxuICB7IHBhdGg6ICcvY2FwdHVyZScsIGNvbXBvbmVudDogQ2FwdHVyZSwgYXM6ICdjYXB0dXJlJyB9LFxuXG4gIHsgcGF0aDogJy9kaXNjb3ZlcnkvOmZpbHRlcicsIGNvbXBvbmVudDogRGlzY292ZXJ5LCBhczogJ2Rpc2NvdmVyeSd9LFxuICB7IHBhdGg6ICcvZGlzY292ZXJ5LzpmaWx0ZXIvOnR5cGUnLCBjb21wb25lbnQ6IERpc2NvdmVyeSwgYXM6ICdkaXNjb3ZlcnknfSxcblxuICB7IHBhdGg6ICcvbWVzc2VuZ2VyJywgY29tcG9uZW50OiAgR2F0aGVyaW5ncywgYXM6ICdtZXNzZW5nZXInfSxcblxuICB7IHBhdGg6ICcvbm90aWZpY2F0aW9ucycsIGNvbXBvbmVudDogTm90aWZpY2F0aW9ucywgYXM6ICdub3RpZmljYXRpb25zJ30sXG5cbiAgeyBwYXRoOiAnL2dyb3Vwcy86ZmlsdGVyJywgY29tcG9uZW50OiBHcm91cHMsIGFzOiAnZ3JvdXBzJ30sXG4gIHsgcGF0aDogJy9ncm91cHMvY3JlYXRlJywgY29tcG9uZW50OiBHcm91cHNDcmVhdG9yLCBhczogJ2dyb3Vwcy1jcmVhdGUnfSxcbiAgeyBwYXRoOiAnL2dyb3Vwcy9wcm9maWxlLzpndWlkJywgY29tcG9uZW50OiBHcm91cHNQcm9maWxlLCBhczogJ2dyb3Vwcy1wcm9maWxlJ30sXG5cblxuICB7IHBhdGg6ICcvOnVzZXJuYW1lJywgY29tcG9uZW50OiBDaGFubmVsLCBhczogJ2NoYW5uZWwnIH0sXG5cbiAgeyBwYXRoOiAnLycsIHJlZGlyZWN0VG86ICcvbmV3c2ZlZWQnIH1cbl0pXG5AVmlldyh7XG4gIHRlbXBsYXRlVXJsOiAnLi90ZW1wbGF0ZXMvaW5kZXguaHRtbCcsXG4gIGRpcmVjdGl2ZXM6IFtUb3BiYXIsIE5hdmlnYXRpb24sIFJvdXRlck91dGxldCwgUm91dGVyTGlua11cbn0pXG5cbmNsYXNzIE1pbmRzIHtcbiAgbmFtZTogc3RyaW5nO1xuXG4gIGNvbnN0cnVjdG9yKCkge1xuICAgIHRoaXMubmFtZSA9ICdNaW5kcyc7XG4gIH1cbn1cblxuYm9vdHN0cmFwKE1pbmRzLCBbUk9VVEVSX0JJTkRJTkdTLCBIVFRQX0JJTkRJTkdTXSk7XG4iXSwic291cmNlUm9vdCI6Ii9zb3VyY2UvIn0=